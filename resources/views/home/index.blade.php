@extends('layout')

@section('conm')

    <div class="bg-blue-50 border border-blue-300 rounded-md p-4 mt-4 text-center">
        <div class="flex justify-center items-center mt-6">
            <span class="text-4xl animate-wave">👋</span>
            <p class="ml-3 text-xl font-semibold">{{auth()->user()->name}} مرحبًا بك </p>
        </div>
    </div>

@endsection




