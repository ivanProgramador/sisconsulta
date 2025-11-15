<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ config('app.name') }} {!! empty($subtitle) ? '' : ' &vellip; ' . e($subtitle) !!}</title>
    <link rel="short icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-200" >

    {{ $slot }}
    
</body>
</html>