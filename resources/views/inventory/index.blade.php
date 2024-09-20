
@extends('layout')
@section('conm')

        <ul class="grid grid-cols-4 text-right container relative navIndex">
            <li class="  rounded "> 
                <a href="{{route('inventory.create')}}"  class="text-sm  py-2   px-2   leading-none rounded-md hover:bg-gray-100" >  انشاء جرد </a>
            </li>
            <li class="   rounded ">
                <a href="{{route('show_inventory')}}"  class="text-sm  py-2   px-2   leading-none rounded-md hover:bg-gray-100" >   قائمة الجرد </a>
            </li>
            {{-- <li class="  rounded "> 
                <a href="{{route('all_sale_refund')}}"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" > تسوية المخزن </a>
            </li>
            <li class="    rounded ">  
                <a href="{{route('purchase_refunds.create')}}"  class="text-sm  py-2   px-2   leading-none rounded-md hover:bg-gray-100" >   قائمة  تسويات الجرد </a>
            </li> --}}
        </ul>
    
        <div class=" container relative  ">
            <button onclick="window.history.back()">رجوع</button>
     
    @yield('inventory')
        </div>

        
@endsection
