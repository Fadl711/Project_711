@extends('layout')
@section('conm')

<ul class="grid grid-cols-4 text-right container relative shadow-md px-2 py-2 bg-gradient-to-t text-white from-indigo-900 to-indigo-600 rounded-md  font-medium capitalize hover:text-blue-600">
    <li class="rounded">
        <a href="{{ route('Receip.create') }}"
        class="py-2 px-4 {{ Route::is('Receip.create') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
         سند جديد
    </a>
    </li>
    <li class="rounded">
        <a href="{{ route('show_all_receipt') }}"
        class="py-2 px-4 {{ Route::is('show_all_receipt') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
        السندات
    </a>
</li>
</ul>
<br>

        <div class=" container relative  ">
{{--             <button onclick="window.history.back()">رجوع</button>
 --}}
    @yield('bonds')
</div>

<script>

</script>
@endsection
