@extends('layout')
@section('conm')
{{-- <x-general-entries/>
components --}}
<x-nav-transfer-restriction/>

@isset($general_entries)
@include('components.general-entries')ئ
@endisset
@endsection
