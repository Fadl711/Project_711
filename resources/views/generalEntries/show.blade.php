@extends('layout')
@section('conm')
{{-- <x-general-entries/>
components --}}
@isset($general_entries)
@include('components.general-entries')
@endisset
@endsection
