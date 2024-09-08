@extends('layout')
@section('conm')

<style>

    .section {
        display: none;
       }

       .section.active {
        display: block;
       }
</style>

<div class="flex justify-between  text-sm  items-center px-4 h-14 p-2  bg-gradient-to-t text-white from-indigo-900 to-indigo-600 rounded-b-2xl shadow-lg font-medium capitalize">
    <button class="focus:border-b-2 border-white focus:outline-none"onclick="showSection(1)">تحليل المنتجات</button>
    <button class="focus:border-b-2 border-white focus:outline-none"onclick="showSection(2)">تحليل المبيعات</button>
    <button class="focus:border-b-2 border-white focus:outline-none"onclick="showSection(3)">تحليل المشتريات</button>
    <button class="focus:border-b-2 border-white focus:outline-none"onclick="showSection(4)">تحليل الأصول </button>
    <button class="focus:border-b-2 border-white focus:outline-none"onclick="showSection(5)">تحليل المدفوعات</button>

  </div>


<div id="section-1" class="section">

    @include('chart.bar')
   </div>
   <div id="section-2" class="section">
    <div class="">
        <div class="">
            <x-filters />
        </div>
    </div>
    @include('chart.sales')

   </div>
   <div id="section-3" class="section">
        <div class="">
        <div class="">
            <x-filters />
        </div>
    </div>

    @include('chart.line')
   </div>
   <div id="section-4" class="section">
       @include('chart.assets')

   </div>
   <div id="section-5" class="section">
    </div>



<script>
let currentSection = null;
var bob = document.getElementById('section-1');
bob.classList.add('active');
function showSection(id) {
 if (id === undefined) {
  id = 1; // show the first section by default
 }

 // إخفاء جميع الأقسام
 var sections = document.querySelectorAll('.section');
 sections.forEach(function(section) {
  section.classList.remove('active');
 });

 // إظهار القسم المحدد
 var section = document.getElementById('section-' + id);
 if (currentSection === section) {
  section.classList.remove('active');
  currentSection = null;
 } else {
  section.classList.add('active');
  currentSection = section;
 }
}




    document.addEventListener('DOMContentLoaded', function() {
        let f1Pressed = false;

        document.addEventListener('keydown', function(event) {
            if (event.key === 'F2') {
                f1Pressed = true;
            }

            if (f1Pressed && event.key === 'f') {
                event.preventDefault(); // منع السلوك الافتراضي لـ F1
                window.location.href = '/home';
            }
        });

        document.addEventListener('keyup', function(event) {
            if (event.key === 'F2') {
                f1Pressed = false;
            }
        });
    });
</script>


@endsection
