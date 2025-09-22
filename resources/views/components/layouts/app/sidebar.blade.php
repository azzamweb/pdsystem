<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900" data-sidebar x-data="{ sidebarOpen: false }" x-init="
            // Auto hide sidebar on mobile when clicking outside
            $nextTick(() => {
                try {
                    document.addEventListener('click', (e) => {
                        if (window.innerWidth < 1024) { // lg breakpoint
                            const sidebar = document.querySelector('[data-sidebar]');
                            const toggle = document.querySelector('[data-sidebar-toggle]');
                            if (sidebar && !sidebar.contains(e.target) && !toggle?.contains(e.target)) {
                                sidebarOpen = false;
                            }
                        }
                    });
                    
                    // Auto hide sidebar on navigation
                    document.addEventListener('livewire:navigated', () => {
                        try {
                            if (window.innerWidth < 1024) {
                                sidebarOpen = false;
                            }
                        } catch (error) {
                            console.warn('Livewire navigation handler failed:', error);
                        }
                    });
                } catch (error) {
                    console.warn('Sidebar auto-hide initialization failed:', error);
                }
            });
        ">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" data-sidebar-toggle />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-dynamic-app-logo />
            </a>

           

            <flux:navlist variant="outline">
                @if(\App\Helpers\PermissionHelper::can('menu.dashboard'))
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif

                @if(\App\Helpers\PermissionHelper::can('menu.documents'))
                <flux:navlist.group :heading="__('Dokumen')" class="grid">
                    <flux:navlist.item icon="document-text" :href="route('documents')" :current="request()->routeIs('documents')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Dokumen') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif

                @if(\App\Helpers\PermissionHelper::can('menu.master-data'))
                <flux:navlist.item icon="users" :href="route('master-data.index')" :current="request()->routeIs('master-data.*')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Master Data') }}</flux:navlist.item>
                @endif

                @if(\App\Helpers\PermissionHelper::can('menu.location-routes'))
                <flux:navlist.item icon="map" :href="route('location-routes.index')" :current="request()->routeIs('location-routes.*')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Ref Lokasi & Rute') }}</flux:navlist.item>
                @endif

                @if(\App\Helpers\PermissionHelper::can('menu.reference-rates'))
                <flux:navlist.item icon="calculator" :href="route('reference-rates.index')" :current="request()->routeIs('reference-rates.*')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Referensi Tarif') }}</flux:navlist.item>
                @endif
            </flux:navlist>

            @if(\App\Helpers\PermissionHelper::can('menu.rekap'))
            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Rekapitulasi')" class="grid">
                    <flux:navlist.item icon="chart-bar" :href="route('rekap.global')" :current="request()->routeIs('rekap.global')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Rekap Global') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('rekap.pegawai')" :current="request()->routeIs('rekap.pegawai')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Rekap Pegawai') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            @endif

            <flux:spacer />

            @if(\App\Helpers\PermissionHelper::can('menu.configuration'))
            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Configuration')" class="grid">
                    @if(\App\Helpers\PermissionHelper::can('menu.organization'))
                    <flux:navlist.item :href="route('organization.show')" icon="building-office-2" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Organisasi') }}</flux:navlist.item>
                    @endif
                    @if(\App\Helpers\PermissionHelper::can('menu.ranks'))
                    <flux:navlist.item icon="shield-check" :href="route('ranks.index')" :current="request()->routeIs('ranks.*')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Data Pangkat') }}</flux:navlist.item>
                    @endif
                    @if(\App\Helpers\PermissionHelper::can('menu.doc-number-formats'))
                    <flux:navlist.item icon="hashtag" :href="route('doc-number-formats.index')" :current="request()->routeIs('doc-number-formats.*')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Format Penomoran Dokumen') }}</flux:navlist.item>
                    @endif
                    @if(\App\Helpers\PermissionHelper::can('menu.number-sequences'))
                    <flux:navlist.item icon="hashtag" :href="route('number-sequences.index')" :current="request()->routeIs('number-sequences.*')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Number Sequence') }}</flux:navlist.item>
                    @endif
                    @if(\App\Helpers\PermissionHelper::can('menu.document-numbers'))
                    <flux:navlist.item icon="document-text" :href="route('document-numbers.index')" :current="request()->routeIs('document-numbers.*')" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Riwayat Nomor Dokumen') }}</flux:navlist.item>
                    @endif
                </flux:navlist.group>
            </flux:navlist>
            @endif

            

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.show')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        {{-- <flux:menu.item :href="route('organization.show')" icon="building-office-2" wire:navigate>{{ __('Organisasi') }}</flux:menu.item> --}}
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" data-sidebar-toggle />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.show')" icon="cog" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Settings') }}</flux:menu.item>
                        <flux:menu.item :href="route('organization.show')" icon="building-office-2" wire:navigate @click="if (window.innerWidth < 1024) sidebarOpen = false">{{ __('Organisasi') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        <script>
            document.addEventListener('alpine:init', () => {
                try {
                    Alpine.data('searchableSelect', (config) => ({
                    options: config.options || [],
                    selectedValue: config.selectedValue || null,
                    placeholder: config.placeholder || 'Pilih opsi...',
                    searchTerm: '',
                    open: false,
                    selectedIndex: 0,

                    init() {
                        // Set initial search term if there's a selected value
                        if (this.selectedValue) {
                            const selectedOption = this.options.find(option => option.id == this.selectedValue);
                            if (selectedOption) {
                                this.searchTerm = selectedOption.text;
                            }
                        }

                        this.$watch('selectedValue', (value) => {
                            if (value) {
                                const selectedOption = this.options.find(option => option.id == value);
                                if (selectedOption) {
                                    this.searchTerm = selectedOption.text;
                                }
                            } else {
                                this.searchTerm = '';
                            }
                        });

                        this.$watch('searchTerm', () => {
                            this.selectedIndex = 0;
                        });
                    },

                    get filteredOptions() {
                        if (!this.searchTerm) {
                            return this.options;
                        }
                        
                        const term = this.searchTerm.toLowerCase();
                        return this.options.filter(option => 
                            option.text.toLowerCase().includes(term) ||
                            option.name.toLowerCase().includes(term) ||
                            (option.nip && option.nip.toLowerCase().includes(term))
                        );
                    },

                    selectOption(option) {
                        this.selectedValue = option.id;
                        this.searchTerm = option.text;
                        this.open = false;
                    },

                    selectNext() {
                        if (this.selectedIndex < this.filteredOptions.length - 1) {
                            this.selectedIndex++;
                        }
                    },

                    selectPrevious() {
                        if (this.selectedIndex > 0) {
                            this.selectedIndex--;
                        }
                    },

                    selectCurrent() {
                        if (this.filteredOptions.length > 0) {
                            this.selectOption(this.filteredOptions[this.selectedIndex]);
                        }
                    }
                }));
                } catch (error) {
                    console.warn('Alpine.js searchableSelect initialization failed:', error);
                }
            });
        </script>

        @livewireScripts
        @fluxScripts
    </body>
</html>
