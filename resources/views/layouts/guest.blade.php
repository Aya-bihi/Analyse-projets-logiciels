<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            background-color: #0a0a0c !important;
            margin: 0;
            padding: 0;
            min-height: 100%;
        }
    </style>
</head>

<body class="font-sans antialiased">

    <!-- Container principal -->
    <div class="min-h-screen flex flex-col items-center justify-center bg-[#0a0a0c]">

        {{ $slot }}

    </div>

</body>
</html>