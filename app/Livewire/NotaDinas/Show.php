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
            'new_status' => 'required|in:DRAFT,APPROVED',
            'status_notes' => 'nullable|string|max:500',
        ]);

        // Validasi transisi status yang diizinkan
        $allowedTransitions = [
            'DRAFT' => ['APPROVED'],
            'APPROVED' => ['DRAFT'],
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

            DB::commit();

            // Update properti lokal agar UI langsung berubah
            $this->notaDinas->status = $this->new_status;

            // Reset form
            $this->resetStatusForm();

            // Flash message
            $statusMessages = [
                'SUBMITTED' => 'Nota Dinas berhasil diajukan untuk review.',
                'APPROVED' => 'Nota Dinas berhasil disetujui.',
                'REJECTED' => 'Nota Dinas ditolak.',
                'DRAFT' => 'Nota Dinas dikembalikan ke status draft.',
            ];

            session()->flash('message', $statusMessages[$this->notaDinas->status] ?? 'Status berhasil diperbarui.');
            
            // Redirect untuk sinkronisasi penuh
            return redirect()->route('nota-dinas.show', $this->notaDinas->id);

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
        $this->notaDinas->load(['participants.user.rank', 'participants.user.position.echelon', 'requestingUnit', 'destinationCity', 'toUser', 'fromUser', 'spt']);

        $participantsOrdered = $this->notaDinas->participants->sort(function ($a, $b) {
            $ea = $a->user?->position?->echelon?->id ?? 999999;
            $eb = $b->user?->position?->echelon?->id ?? 999999;
            if ($ea !== $eb) return $ea <=> $eb; // ASC

            $ra = $a->user?->rank?->id ?? 0;
            $rb = $b->user?->rank?->id ?? 0;
            if ($ra !== $rb) return $rb <=> $ra; // DESC (lebih tinggi dulu)

            $na = (string)($a->user?->nip ?? '');
            $nb = (string)($b->user?->nip ?? '');
            return strcmp($na, $nb); // ASC
        })->values();

        return view('livewire.nota-dinas.show', [
            'notaDinas' => $this->notaDinas,
            'participantsOrdered' => $participantsOrdered,
        ]);
    }
}
