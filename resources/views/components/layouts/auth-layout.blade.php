<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ config('app.name') }} {!! empty($subtitle) ? '' : ' &vellip; ' . e($subtitle) !!}</title>
    <link rel="short icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">

    {{-- datatables --}}
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}">
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>

    {{-- coloris --}}
    <link rel="stylesheet" href="{{ asset('assets/coloris/coloris.min.css') }}">
    <script src="{{ asset('assets/coloris/coloris.min.js') }}"></script>

    {{--helper --}}
    <script src="{{ asset('app/js/helper.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-200" >
   
    {{-- barra de topo do usuario --}}
    <x-layouts.user_top_bar />

   {{-- menu horizontal principal --}}
    <x-layouts.main_menu />

   {{-- conteudo principal --}}

   <div class="p-8">

      {{ $slot }}
      
   </div>


    
    
</body>
</html>