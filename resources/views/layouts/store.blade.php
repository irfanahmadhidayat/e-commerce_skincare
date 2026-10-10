<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Toko Skincare') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                <a href="{{ route('home') }}" wire:navigate class="text-lg font-bold text-pink-600">Toko <span class="text-gray-800">Skincare</span></a>
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" wire:navigate class="text-gray-700 hover:text-pink-600">Beranda</a>
                    <a href="{{ route('catalog') }}" wire:navigate class="text-gray-700 hover:text-pink-600">Katalog</a>
                </div>
                <div class="flex items-center gap-3">
                    <livewire:store-cart-badge />
                    @auth
                        <a href="{{ route('pesanan.index') }}" wire:navigate class="text-gray-700 hover:text-pink-600" title="Pesanan Saya">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </a>
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button type="button" @click="open = ! open" class="flex items-center gap-1.5 text-sm font-medium text-gray-700 hover:text-pink-600">
                                <span class="max-w-32 truncate">{{ auth()->user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition @click="open = false" class="absolute right-0 mt-2 w-44 bg-white border border-gray-100 rounded-lg shadow-lg py-1 z-10">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-pink-600">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (isset($header))
                <header class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $header }}</h1>
                </header>
            @endif
            {{ $slot }}
        </main>

        <footer class="border-t bg-white mt-12">
            <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Toko Skincare
            </div>
        </footer>
        @stack('js')
    </body>
</html>