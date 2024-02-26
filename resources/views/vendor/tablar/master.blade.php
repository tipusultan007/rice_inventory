<!doctype html>
<html lang="{{ Config::get('app.locale') }}" {!! config('tablar.layout') == 'rtl' ? 'dir="rtl"' : '' !!}>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')
    {{-- Title --}}
    <title>
        @yield('title_prefix', config('tablar.title_prefix', ''))
        @yield('title', config('tablar.title', 'Tablar'))
        @yield('title_postfix', config('tablar.title_postfix', ''))
    </title>
    <script type="module" src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.0/b-3.0.0/sb-1.7.0/datatables.min.js"></script>
    <!-- CSS files -->
    @if(config('tablar','vite'))
        @vite('resources/js/app.js')
    @endif

    @yield('tablar_css')

    <link rel="stylesheet" href="{{asset('fonts/style.css')}}">
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.0/b-3.0.0/sb-1.7.0/datatables.min.css" rel="stylesheet">
</head>
<body>
@yield('body')
@include('tablar::extra.modal')

@yield('tablar_js')
@yield('scripts')

</body>
</html>
