<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\Spt;
use App\Models\NotaDinas;
use Livewire\Attributes\Lazy;

#[Lazy]
class SptTable extends Component
{
    public $notaDinasId;

    public $selectedSptId;

    public function mount($notaDinasId = null)
    {
        $this->notaDinasId = $notaDinasId;
    }

    public function selectSpt($sptId)
    {
        $this->selectedSptId = $sptId;
        $this->dispatch('spt-selected', sptId: $sptId);
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="animate-pulse">
            <div class="bg-gray-50 dark:bg-gray-800 px-6 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex space-x-4">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded flex-1"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded flex-1"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded flex-1"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded flex-1"></div>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <div class="px-6 py-4">
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        </div>
                        <div class="flex-1">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
                        </div>
                        <div class="flex-1">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
                        </div>
                        <div class="flex-1">
                            <div class="flex space-x-2">
                                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                        </div>
                        <div class="flex-1">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
                        </div>
                        <div class="flex-1">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
                        </div>
                        <div class="flex-1">
                            <div class="flex space-x-2">
                                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-16"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        $spts = $this->notaDinasId 
            ? Spt::where('nota_dinas_id', $this->notaDinasId)
                ->with(['notaDinas.requestingUnit', 'signedByUser', 'sppds'])
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        $notaDinas = $this->notaDinasId 
            ? NotaDinas::find($this->notaDinasId)
            : null;

        return view('livewire.documents.spt-table', [
            'spts' => $spts,
            'notaDinas' => $notaDinas
        ]);
    }
}