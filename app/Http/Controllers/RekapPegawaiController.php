<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Spt;
use App\Models\Unit;
use App\Models\Position;
use App\Models\Rank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapPegawaiController extends Controller
{
    public function generatePdf(Request $request)
    {
        // Get filter parameters
        $search = $request->get('search', '');
        $unit_filter = $request->get('unit_filter', '');
        $position_filter = $request->get('position_filter', '');
        $rank_filter = $request->get('rank_filter', '');
        $selected_month = $request->get('selected_month', Carbon::now()->format('m'));
        $selected_year = $request->get('selected_year', Carbon::now()->format('Y'));

        // Build query
        $query = User::with([
            'unit',
            'position.echelon',
            'rank',
            'travelGrade'
        ]);

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('gelar_depan', 'like', '%' . $search . '%')
                  ->orWhere('gelar_belakang', 'like', '%' . $search . '%');
            });
        }

        if ($unit_filter) {
            $query->where('unit_id', $unit_filter);
        }

        if ($position_filter) {
            $query->where('position_id', $position_filter);
        }

        if ($rank_filter) {
            $query->where('rank_id', $rank_filter);
        }

        // Sort by position echelon, rank, and NIP
        $query->orderBy('position_id', 'asc')
              ->orderBy('rank_id', 'asc')
              ->orderBy('nip', 'asc');

        $pegawai = $query->get();

        // Get SPT data for the selected month
        $startDate = Carbon::createFromDate($selected_year, $selected_month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $sptData = Spt::with(['notaDinas.participants.user', 'notaDinas.originPlace', 'notaDinas.destinationCity'])
            ->whereHas('notaDinas', function($q) use ($startDate, $endDate) {
                $q->where(function($q2) use ($startDate, $endDate) {
                    $q2->where('start_date', '<=', $endDate)
                        ->where('end_date', '>=', $startDate);
                });
            })
            ->get();

        // Create schedule data
        $scheduleData = [];
        foreach ($pegawai as $p) {
            $scheduleData[$p->id] = [];
            
            // Get SPTs where this user is a participant
            $userSpts = $sptData->filter(function($spt) use ($p) {
                return $spt->notaDinas->participants->contains('user_id', $p->id);
            });

            foreach ($userSpts as $spt) {
                $startDate = Carbon::parse($spt->notaDinas->start_date)->startOfDay();
                $endDate = Carbon::parse($spt->notaDinas->end_date)->endOfDay();
                
                // Include if any part of the trip is in the selected month
                $tripStartMonth = $startDate->format('Y-m');
                $tripEndMonth = $endDate->format('Y-m');
                $selectedMonth = $selected_year . '-' . $selected_month;
                
                if ($tripStartMonth === $selectedMonth || $tripEndMonth === $selectedMonth || 
                    ($startDate->lt(Carbon::createFromDate($selected_year, $selected_month, 1)) && 
                     $endDate->gt(Carbon::createFromDate($selected_year, $selected_month, 1)->endOfMonth()))) {
                    $scheduleData[$p->id][] = [
                        'spt' => $spt,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'doc_no' => $spt->doc_no,
                        'hal' => $spt->notaDinas->hal,
                        'origin_place' => $spt->notaDinas->originPlace->name ?? '-',
                        'destination_city' => $spt->notaDinas->destinationCity->name ?? '-'
                    ];
                }
            }
        }

        // Get days in selected month
        $daysInMonth = Carbon::createFromDate($selected_year, $selected_month, 1)->daysInMonth;
        $monthName = Carbon::createFromDate($selected_year, $selected_month, 1)->format('F Y');

        // Generate PDF
        $pdf = PDF::loadView('rekap.pegawai.pdf', [
            'pegawai' => $pegawai,
            'scheduleData' => $scheduleData,
            'daysInMonth' => $daysInMonth,
            'monthName' => $monthName,
            'selectedMonth' => $selected_month,
            'selectedYear' => $selected_year,
        ]);

        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->stream("rekap-pegawai-{$monthName}.pdf");
    }
}
