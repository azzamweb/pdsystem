<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Master Data')" class="grid">
                    <flux:navlist.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.*')" wire:navigate>{{ __('Data Pegawai') }}</flux:navlist.item>
                    <flux:navlist.item icon="building-office" :href="route('units.index')" :current="request()->routeIs('units.*')" wire:navigate>{{ __('Data Unit') }}</flux:navlist.item>
                    <flux:navlist.item icon="briefcase" :href="route('positions.index')" :current="request()->routeIs('positions.*')" wire:navigate>{{ __('Data Jabatan') }}</flux:navlist.item>
                   
                </flux:navlist.group>

                <flux:navlist.group :heading="__('Ref Lokasi & Rute')" class="grid">
                   
                    <flux:navlist.item icon="map" :href="route('provinces.index')" :current="request()->routeIs('provinces.*')" wire:navigate>{{ __('Data Provinsi') }}</flux:navlist.item>
                    <flux:navlist.item icon="building-office" :href="route('cities.index')" :current="request()->routeIs('cities.*')" wire:navigate>{{ __('Data Kota/Kabupaten') }}</flux:navlist.item>
                    <flux:navlist.item icon="map-pin" :href="route('districts.index')" :current="request()->routeIs('districts.*')" wire:navigate>{{ __('Data Kecamatan') }}</flux:navlist.item>
                    <flux:navlist.item icon="building-office-2" :href="route('org-places.index')" :current="request()->routeIs('org-places.*')" wire:navigate>{{ __('Data Kedudukan') }}</flux:navlist.item>
                    <flux:navlist.item icon="truck" :href="route('transport-modes.index')" :current="request()->routeIs('transport-modes.*')" wire:navigate>{{ __('Data Moda Transportasi') }}</flux:navlist.item>
                    <flux:navlist.item icon="map" :href="route('travel-routes.index')" :current="request()->routeIs('travel-routes.*')" wire:navigate>{{ __('Data Rute Perjalanan') }}</flux:navlist.item>
                    <flux:navlist.item icon="star" :href="route('travel-grades.index')" :current="request()->routeIs('travel-grades.*')" wire:navigate>{{ __('Data Tingkatan Perjalanan') }}</flux:navlist.item>
                    <flux:navlist.item icon="users" :href="route('user-travel-grade-maps.index')" :current="request()->routeIs('user-travel-grade-maps.*')" wire:navigate>{{ __('Mapping Tingkatan Pegawai') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Configuration')" class="grid">

                <flux:navlist.item :href="route('organization.show')" icon="building-office-2" wire:navigate>{{ __('Organisasi') }}</flux:navlist.item>
                <flux:navlist.item icon="shield-check" :href="route('ranks.index')" :current="request()->routeIs('ranks.*')" wire:navigate>{{ __('Data Pangkat') }}</flux:navlist.item>
        </flux:navlist.group>
            </flux:navlist>

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
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

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
                        <flux:menu.item :href="route('profile.show')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        <flux:menu.item :href="route('organization.show')" icon="building-office-2" wire:navigate>{{ __('Organisasi') }}</flux:menu.item>
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

        @livewireScripts
        @fluxScripts
    </body>
</html>
