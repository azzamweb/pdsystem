<?php

namespace App\Http\Controllers;

use App\Models\NotaDinas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaDinasController extends Controller
{
    public function generatePdf(NotaDinas $notaDinas)
    {
        // Load relationships yang diperlukan
        $notaDinas->load(['participants.user', 'requestingUnit', 'destinationCity', 'toUser', 'fromUser', 'spt']);
        
        // Generate PDF
        $pdf = Pdf::loadView('nota-dinas.pdf', [
            'notaDinas' => $notaDinas
        ]);
        
        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Set options untuk hasil yang lebih baik
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial',
            'chroot' => public_path(),
        ]);
        
        // Tampilkan preview PDF di browser menggunakan stream() dengan Attachment => false
        $filename = 'Nota_Dinas_' . str_replace(['/', '\\'], '-', $notaDinas->doc_no) . '.pdf';
        
        return $pdf->stream("$filename", ["Attachment" => false]);
    }
    

    
    public function downloadPdf(NotaDinas $notaDinas)
    {
        // Load relationships yang diperlukan
        $notaDinas->load(['participants.user', 'requestingUnit', 'destinationCity', 'toUser', 'fromUser', 'spt']);
        
        // Generate PDF
        $pdf = Pdf::loadView('nota-dinas.pdf', [
            'notaDinas' => $notaDinas
        ]);
        
        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Set options untuk hasil yang lebih baik
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial',
            'chroot' => public_path(),
        ]);
        
        // Download PDF dengan nama file yang sesuai
        $filename = 'Nota_Dinas_' . str_replace(['/', '\\'], '-', $notaDinas->doc_no) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
