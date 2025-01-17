<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SEO Analyzer')</title>


    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link href="{{ asset('assets/frontend/vendor/fontawesome/css/all.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/css/style.css') }}" rel="stylesheet">
    @livewireStyles


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <livewire:frontend.theme.navbar />

    @yield('content')


    <script src="{{ asset('assets/frontend/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

    @livewireScripts
</body>

</html>