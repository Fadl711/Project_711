
@extends('layout')
@section('conm')

        <ul class="grid grid-cols-4 text-right container relative shadow-md px-2 py-2  bg-white">
            <li class="  rounded "> 
             
                <a href="{{route('sale_refunds.create')}}"  class="text-sm  py-2   px-2   leading-none rounded-md hover:bg-gray-100" >    مردود مبيعات </a>
            </li>
            <li class="   rounded ">
                <a href="{{route('all_restrictions_show')}}"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" > القيود</a>

            </li>
            <li class="  rounded "> 
                <a href="{{route('purchase_refunds.create')}}"  class="text-sm  py-2   px-2   leading-none rounded-md hover:bg-gray-100" >    مردود المشتريات </a>

            </li>
            <li class="    rounded ">  
                <a href="{{route('all_exchange_bonds')}}" id="Accountbalancing"  class="text-sm py-2 px-2  rounded-md hover:bg-gray-100" >  المدفوعات</a>
            </li>

        </ul>
    
        <div class=" container relative  ">
            <button onclick="window.history.back()">رجوع</button>
     
    @yield('refunds')
</div>

<script>
  
</script>
@endsection
