@extends('layout')
@section('conm')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">General Ledger</h1>
    @livewire('general-ledger') <!-- استدعاء مكون Livewire -->
</div>
    @endsection