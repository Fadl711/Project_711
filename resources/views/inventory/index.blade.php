@extends('layout')
@section('conm')
<style>
    .select2-container--default .select2-dropdown {
    max-height: 200px; /* ارتفاع القائمة */
    overflow-y: auto; /* تمكين التمرير إذا تجاوز المحتوى الارتفاع */
}
.select2-container--default .select2-selection--single {
    height: 40px; /* ارتفاع العنصر الأساسي */
    line-height: 45px; لتوسيط النص عموديًا
}
.select2-container--default .select2-selection__rendered {
    padding-top: 5px; /* تحسين النصوص */
}
</style>
<x-nav-inventory/>
<br>
       
    @yield('inventory')
        </div>

        
@endsection
