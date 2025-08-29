<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\NotaDinas;
use App\Models\Spt;
use App\Models\Sppd;


class MainPage extends Component
{
    public $selectedNotaDinasId = null;
    public $selectedSptId = null;
    public $selectedSppdId = null;
    
    public $selectedNotaDinas = null;
    public $selectedSpt = null;
    public $selectedSppd = null;

    // Computed properties for child components
    public function getCurrentNotaDinas()
    {
        if ($this->selectedNotaDinasId) {
            return NotaDinas::with(['spt.sppds'])->find($this->selectedNotaDinasId);
        }
        return null;
    }

    public function getCurrentSpt()
    {
        if ($this->selectedSptId) {
            return Spt::with(['sppds'])->find($this->selectedSptId);
        }
        return null;
    }

    #[On('ndSelected')]
    public function handleNotaDinasSelected($notaDinasId)
    {
        $this->selectedNotaDinasId = $notaDinasId;
        $this->selectedSptId = null;
        $this->selectedSppdId = null;
        
        // Load the actual Nota Dinas data
        $this->selectedNotaDinas = NotaDinas::with(['spt.sppds', 'spt.notaDinas.originPlace', 'spt.notaDinas.destinationCity'])->find($notaDinasId);
        
        // Dispatch specific events to child components
        $this->dispatch('loadSpts', $notaDinasId);
        $this->dispatch('clearSppds');
    }

    #[On('sptSelected')]
    public function handleSptSelected($sptId)
    {
        $this->selectedSptId = $sptId;
        $this->selectedSppdId = null;
        // Persist the selected SPT object for downstream actions (e.g., createLaporanPd)
        $this->selectedSpt = Spt::with(['sppds', 'notaDinas.originPlace', 'notaDinas.destinationCity'])->find($sptId);

        // Dispatch specific event to SppdTable
        $this->dispatch('loadSppds', $sptId);
    }

    #[On('sppdSelected')]
    public function handleSppdSelected($sppdId)
    {
        $this->selectedSppdId = $sppdId;
        // Load the actual SPPD data
        $this->selectedSppd = Sppd::with(['spt.notaDinas.originPlace', 'spt.notaDinas.destinationCity'])->find($sppdId);
    }

    #[On('refreshAll')]
    public function refreshData()
    {
        // Refresh selected data with proper eager loading
        if ($this->selectedNotaDinasId) {
            $this->selectedNotaDinas = NotaDinas::with(['spt.sppds', 'spt.notaDinas.originPlace', 'spt.notaDinas.destinationCity'])->find($this->selectedNotaDinasId);
        }
        if ($this->selectedSptId) {
            $this->selectedSpt = Spt::with(['sppds', 'notaDinas.originPlace', 'notaDinas.destinationCity'])->find($this->selectedSptId);
        }
        if ($this->selectedSppdId) {
            $this->selectedSppd = Sppd::with(['spt.notaDinas.originPlace', 'spt.notaDinas.destinationCity'])->find($this->selectedSppdId);
        }
        
        // Force refresh of trip report data to ensure latest data is displayed
        if ($this->selectedSptId) {
            // This will trigger a re-render of the trip report section
            $this->dispatch('trip-report-refreshed');
        }
    }

    public function mount()
    {
        // Check for query parameters first
        $notaDinasId = request()->query('nota_dinas_id');
        $sptId = request()->query('spt_id');
        $sppdId = request()->query('sppd_id');
        $reportCreated = request()->query('report_created');
        $reportUpdated = request()->query('report_updated');
        
        if ($notaDinasId) {
            $this->selectedNotaDinasId = $notaDinasId;
            // Load the actual Nota Dinas data
            $this->selectedNotaDinas = NotaDinas::with(['spt.sppds'])->find($notaDinasId);
            $this->dispatch('loadSpts', $notaDinasId);
            
            if ($sptId) {
                $this->selectedSptId = $sptId;
                // Load the actual SPT data
                $this->selectedSpt = Spt::with(['sppds'])->find($sptId);
                $this->dispatch('loadSppds', $sptId);
                
                if ($sppdId) {
                    $this->selectedSppdId = $sppdId;
                    // Load the actual SPPD data
                    $this->selectedSppd = Sppd::find($sppdId);
                }
            }
            
            // If report was created or updated, ensure we show success message and refresh data
            if ($reportCreated) {
                session()->flash('message', 'Laporan perjalanan dinas berhasil dibuat dan ditampilkan.');
                $this->refreshData();
            } elseif ($reportUpdated) {
                session()->flash('message', 'Laporan perjalanan dinas berhasil diperbarui dan ditampilkan.');
                $this->refreshData();
            }
        } else {
            // Initialize with latest Nota Dinas if available
            $latestNd = NotaDinas::with(['spt.sppds'])->latest('created_at')->first();
            if ($latestNd) {
                $this->selectedNotaDinasId = $latestNd->id;
                $this->selectedNotaDinas = $latestNd;
                // Dispatch events to load child components
                $this->dispatch('loadSpts', $latestNd->id);
                if ($latestNd->spt) {
                    $this->selectedSptId = $latestNd->spt->id;
                    $this->selectedSpt = $latestNd->spt;
                    $this->dispatch('loadSppds', $latestNd->spt->id);
                }
            }
        }
    }





    public function createLaporanPd()
    {
        // Use the selected SPT ID to avoid null object issues across requests
        if (!$this->selectedSptId) {
            session()->flash('error', 'Pilih SPT terlebih dahulu');
            return;
        }
        
        // Check if trip report already exists
        $existingReport = \App\Models\TripReport::where('spt_id', $this->selectedSptId)->first();
        if ($existingReport) {
            session()->flash('error', 'Laporan perjalanan dinas untuk SPT ini sudah ada');
            return;
        }
        
        // Redirect to create trip report with SPT ID as query parameter
        return $this->redirect(route('trip-reports.create') . '?spt_id=' . $this->selectedSptId);
    }

    public function createKwitansi()
    {
        // Use the selected SPT ID to avoid null object issues across requests
        if (!$this->selectedSptId) {
            session()->flash('error', 'Pilih SPT terlebih dahulu');
            return;
        }
        
        // Check if there are SPPDs available for this SPT
        $sppds = \App\Models\Sppd::where('spt_id', $this->selectedSptId)->get();
        if ($sppds->isEmpty()) {
            session()->flash('error', 'Tidak ada SPPD yang tersedia untuk dibuatkan kwitansi');
            return;
        }
        
        // Check if all SPPDs already have receipts
        $sppdsWithoutReceipts = $sppds->filter(function($sppd) {
            return !$sppd->receipts()->exists();
        });
        
        if ($sppdsWithoutReceipts->isEmpty()) {
            session()->flash('error', 'Semua SPPD sudah memiliki kwitansi');
            return;
        }
        
        // Redirect to create receipt page
        return $this->redirect(route('receipts.create') . '?spt_id=' . $this->selectedSptId);
    }

    public function deleteTripReport($tripReportId)
    {
        try {
            $tripReport = \App\Models\TripReport::findOrFail($tripReportId);
            
            // Store current state before deletion
            $notaDinasId = $tripReport->spt->nota_dinas_id;
            $sptId = $tripReport->spt_id;
            $firstSppd = $tripReport->spt->sppds()->first();
            $sppdId = $firstSppd ? $firstSppd->id : null;
            
            // Supporting documents now relate to Nota Dinas, not Trip Report
            // No need to delete supporting documents when deleting trip report
            
            // Delete the trip report
            $tripReport->delete();
            
            session()->flash('message', 'Laporan perjalanan dinas berhasil dihapus.');
            
            // Refresh the data to update the UI
            $this->refreshData();
            
            // Maintain the current state after deletion
            $this->selectedNotaDinasId = $notaDinasId;
            $this->selectedSptId = $sptId;
            if ($sppdId) {
                $this->selectedSppdId = $sppdId;
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus laporan perjalanan dinas: ' . $e->getMessage());
        }
    }

    public function deleteSupportingDocument($documentId)
    {
        try {
            $document = \App\Models\SupportingDocument::findOrFail($documentId);
            
            // Store document info for confirmation message
            $documentTitle = $document->title;
            
            // Delete the document
            $document->delete();
            
            session()->flash('message', "Dokumen '{$documentTitle}' berhasil dihapus.");
            
            // Refresh the data to update the UI
            $this->refreshData();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus dokumen pendukung: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.documents.main-page');
    }
}
