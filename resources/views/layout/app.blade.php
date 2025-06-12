<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PartnerGO') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        
        <link href="{{ asset('css/layout.css') }}" rel="stylesheet">

        @stack('styles')
    </head>

    <body class="main-body">
        <div class="page-wrapper">
            @if (Auth::check())
                @include('layout.navbar_logged')
            @else
                @include('layout.navbar_guest')
            @endif

            <main>
                @yield('content')
            </main>
            
            <footer class="footer">
                <div class="container">
                    <div class="footer-text">
                        &copy; {{ date('Y') }} PartnerGO. All rights reserved.
                    </div>
                </div>
            </footer>

            <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

            <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

            <script src="{{ asset('js/app.js') }}" defer></script>
            
            @stack('scripts')
            
        </div>
    </body>
</html>