@extends('layout')

@section('conm')
<style>
    @keyframes wave {
    0% { transform: rotate(0deg); }
    15% { transform: rotate(14deg); }
    30% { transform: rotate(-8deg); }
    40% { transform: rotate(14deg); }
    50% { transform: rotate(-4deg); }
    60% { transform: rotate(10deg); }
    100% { transform: rotate(0deg); }
  }

  .animate-wave {
    animation: wave 2s infinite;
    transform-origin: bottom center;
  }
</style>

    <div class="bg-blue-100 border border-blue-300 rounded-md p-4 mt-4 text-center">
        <div class="flex justify-center items-center mt-6">
            <p class="ml-3 text-2xl font-semibold"> مرحبًا بك {{auth()->user()->name}} </p>
            <span class="text-4xl animate-wave">👋</span>
        </div>
    </div>

@endsection




