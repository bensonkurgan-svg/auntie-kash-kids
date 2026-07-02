<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auntie Kash Kids Academy') — Auntie Kash Kids</title>
    <meta name="description" content="@yield('meta_description', 'Live online enrichment programs for children aged 5-16. Building confidence, creativity, and a lifelong love of learning.')">
    <link rel="icon" href="{{ asset('images/round-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
    <div class="scroll-progress"></div>
    @include('partials.navbar')
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
