@extends('layout')
@section('conm')
    <x-nav-production-system />
    <style>
        select {
            appearance: auto;
            background-image: none
        }
    </style>
    <div id="main-content">
        @yield('productionSystem')
    </div>

    <script src="{{ url('/assets/ajax-navigation.js') }}"></script>
@endsection
