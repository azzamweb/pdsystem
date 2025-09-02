<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function generatePdf(Receipt $receipt)
    {
        // Load relationships yang diperlukan untuk PDF kwitansi lengkap
        $receipt->load([
            'sppd.spt.notaDinas.participants.user',
            'sppd.spt.notaDinas.originPlace',
            'sppd.spt.notaDinas.destinationCity',
            'sppd.spt.notaDinas.destinationCity.province',
            'sppd.signedByUser.position',
            'sppd.signedByUser.unit',
            'sppd.signedByUser.rank',
            'payeeUser.position',
            'payeeUser.rank',
            'payeeUser.unit',
            'treasurerUser.position',
            'treasurerUser.unit',
            'treasurerUser.rank',
            'travelGrade',
            'lines'
        ]);
        
        // Generate PDF
        $pdf = Pdf::loadView('receipts.pdf', [
            'receipt' => $receipt
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
        $filename = 'Kwitansi_' . ($receipt->receipt_no ?: 'Manual') . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->stream("$filename", ["Attachment" => false]);
    }
    
    public function downloadPdf(Receipt $receipt)
    {
        // Load relationships yang diperlukan untuk PDF kwitansi lengkap
        $receipt->load([
            'sppd.spt.notaDinas.participants.user',
            'sppd.spt.notaDinas.originPlace',
            'sppd.spt.notaDinas.destinationCity',
            'sppd.spt.notaDinas.destinationCity.province',
            'sppd.signedByUser.position',
            'sppd.signedByUser.unit',
            'sppd.signedByUser.rank',
            'payeeUser.position',
            'payeeUser.rank',
            'payeeUser.unit',
            'treasurerUser.position',
            'treasurerUser.unit',
            'treasurerUser.rank',
            'travelGrade',
            'lines'
        ]);
        
        // Generate PDF
        $pdf = Pdf::loadView('receipts.pdf', [
            'receipt' => $receipt
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
        $filename = 'Kwitansi_' . ($receipt->receipt_no ?: 'Manual') . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
