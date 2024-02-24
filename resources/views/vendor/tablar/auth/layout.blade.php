<!doctype html>
<html lang="{{ Config::get('app.locale') }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- CSS files -->
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="{{asset('fonts/style.css')}}">
</head>
<body class=" border-top-wide border-primary d-flex flex-column">
<div class="page page-center">
    @yield('content')
</div>

<footer class="footer footer-transparent d-print-none">
    <div class="container-xl">
        <div class="text-center">&copy; 2024 মেসার্স এস.এ রাইচ এজেন্সী। Developed by
            <a target="_blank" href="https://umairit.com"
               class="link-secondary">Umair IT</a>.</div>
    </div>
</footer>

</body>
</html>
