@extends('layout')

<div class="flex justify-evenly  text-sm  items-center px-4 h-14 p-2  bg-gradient-to-t text-white from-indigo-900 to-indigo-600 rounded-b-2xl shadow-lg font-medium capitalize">
    <a href="{{route('products.index')}}" class=" {{ Request::is('products') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white "> المنتجات</button>
    <a href="{{route('products.create')}}" class="{{ Request::is('products/create') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white ">اضافة منتاج </button>
    <a href="{{route('Category.create')}}" class="{{ Request::is('products/Category') ? '   border-b-2  font-bold text-xl' : 'border-b-0' }} border-white ">اضافة وحده </button>

</div>
{{-- @extends('products.app') --}}
@section('conm')



@yield('prod')




@endsection
