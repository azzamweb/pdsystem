<?php

namespace App\Livewire\Documents;

use Livewire\Component;
use App\Models\Sppd;
use Livewire\Attributes\Lazy;

#[Lazy]
class SppdTable extends Component
{
    public $sptId;

    public $selectedSppdId;

    public function mount($sptId = null)
    {
        $this->sptId = $sptId;
    }

    public function selectSppd($sppdId)
    {
        $this->selectedSppdId = $sppdId;
        $this->dispatch('sppd-selected', sppdId: $sppdId);
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
        $sppds = $this->sptId 
            ? Sppd::where('spt_id', $this->sptId)
                ->with(['spt.notaDinas.participants', 'signedByUser', 'receipts'])
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        return view('livewire.documents.sppd-table', [
            'sppds' => $sppds
        ]);
    }
}