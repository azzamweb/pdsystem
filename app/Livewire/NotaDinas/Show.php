<?php

namespace App\Livewire\NotaDinas;

use App\Models\NotaDinas;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.app')]
class Show extends Component
{
    public NotaDinas $notaDinas;
    
    // Properties untuk form update status
    public $new_status = '';
    public $status_notes = '';

    public function mount(NotaDinas $notaDinas)
    {
        $this->notaDinas = $notaDinas;
    }

    public function updateStatus()
    {
        $this->validate([
            'new_status' => 'required|in:DRAFT,SUBMITTED,APPROVED,REJECTED',
            'status_notes' => 'nullable|string|max:500',
        ]);

        // Validasi transisi status yang diizinkan
        $allowedTransitions = [
            'DRAFT' => ['SUBMITTED'],
            'SUBMITTED' => ['APPROVED', 'REJECTED'],
            'APPROVED' => ['DRAFT'],
            'REJECTED' => ['DRAFT', 'SUBMITTED'],
        ];

        $currentStatus = $this->notaDinas->status;
        $allowedNextStatuses = $allowedTransitions[$currentStatus] ?? [];

        if (!in_array($this->new_status, $allowedNextStatuses)) {
            $this->addError('new_status', 'Transisi status tidak diizinkan.');
            return;
        }

        DB::beginTransaction();
        try {
            $oldStatus = $this->notaDinas->status;
            
            // Update status
            $this->notaDinas->update([
                'status' => $this->new_status,
            ]);

            // Log perubahan status (opsional - bisa ditambahkan ke tabel audit)
            // StatusChangeLog::create([
            //     'nota_dinas_id' => $this->notaDinas->id,
            //     'old_status' => $oldStatus,
            //     'new_status' => $this->new_status,
            //     'changed_by' => auth()->id(),
            //     'notes' => $this->status_notes,
            // ]);

            DB::commit();

            // Reset form
            $this->resetStatusForm();

            // Flash message
            $statusMessages = [
                'SUBMITTED' => 'Nota Dinas berhasil diajukan untuk review.',
                'APPROVED' => 'Nota Dinas berhasil disetujui.',
                'REJECTED' => 'Nota Dinas ditolak.',
                'DRAFT' => 'Nota Dinas dikembalikan ke status draft.',
            ];

            session()->flash('message', $statusMessages[$this->new_status] ?? 'Status berhasil diperbarui.');
            
            // Refresh data
            $this->notaDinas->refresh();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    public function resetStatusForm()
    {
        $this->new_status = '';
        $this->status_notes = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $this->notaDinas->load(['participants.user', 'requestingUnit', 'destinationCity', 'toUser', 'fromUser']);
        return view('livewire.nota-dinas.show', [
            'notaDinas' => $this->notaDinas
        ]);
    }
}
