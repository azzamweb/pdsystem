<?php

namespace App\Http\Controllers;

use App\Models\TripReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TripReportController extends Controller
{
    public function pdf(TripReport $tripReport)
    {
        // Load relationships
        $tripReport->load(['spt.notaDinas.originPlace', 'spt.notaDinas.destinationCity', 'createdByUser']);
        
        // Generate PDF
        $pdf = Pdf::loadView('trip-reports.pdf', compact('tripReport'));
        
        return $pdf->stream('laporan-perjalanan-dinas-' . $tripReport->id . '.pdf');
    }
}
