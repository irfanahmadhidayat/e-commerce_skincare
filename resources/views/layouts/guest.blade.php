<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Toko Skincare') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 flex flex-col min-h-screen">
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                <a href="{{ route('home') }}" wire:navigate class="text-lg font-bold text-pink-600">Toko <span class="text-gray-800">Skincare</span></a>
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" wire:navigate class="text-gray-700 hover:text-pink-600">Beranda</a>
                    <a href="{{ route('catalog') }}" wire:navigate class="text-gray-700 hover:text-pink-600">Katalog</a>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" wire:navigate class="text-sm text-gray-700 hover:text-pink-600">Masuk</a>
                    <a href="{{ route('register') }}" wire:navigate class="text-sm bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700">Daftar</a>
                </div>
            </div>
        </nav>

        <main class="flex-1 flex items-center justify-center px-4 py-12 bg-gradient-to-br from-pink-50 via-white to-gray-50">
            <div class="w-full max-w-md">
                <div class="bg-white border border-gray-100 rounded-2xl shadow-lg p-8">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <footer class="border-t bg-white mt-auto">
            <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Toko Skincare
            </div>
        </footer>
    </body>
</html>