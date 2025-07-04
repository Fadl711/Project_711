<!DOCTYPE html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">


    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery/dist/jquery.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://www.w3schools.com/js/myScript.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/fontawesome-free/css/all.min.css') }}">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    {{--         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    {{--         <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
 --}} {{--  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}


    <style>
        .sel {
            appearance: auto;
            background-image: none
        }

        .select2-container {
            width: 100% !important;
            /* فرض العرض الكامل */
        }

        .select2-selection {
            width: 100% !important;
            /* ضمان عرض عنصر select2 بشكل كامل */
        }
    </style>

    {{-- fiex_assets --}}
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
    {{--         <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
 --}}{{-- fiex_assets --}}
    <script src="{{ asset('assets/flowbite/dist/flowbite.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Scripts -->
</head>

<body class="justify-center bg-[#f1efefc9] overflow-hidden">
    @include('layouts.navigation')
    <div class="flex max-h-screen">

        @include('includes.swip')

        <div class=" xl:container mx-10  relative  overflow-auto">
            @yield('conm')
        </div>
    </div>



    <script src="{{ asset('assets\js\sweetalert2\dist\sweetalert2.all.min.js') }}" defer></script>
    <script src="{{ asset('assets\js\alpinejs\dist\cdn.min.js') }}" defer></script>


</body>

</html>
