@extends('layout')
@section('conm')

        <ul class="grid grid-cols-4 text-right container relative shadow-md px-2 py-2  bg-white">
            <li class="   "> 
             
                <a href="{{route('create.index')}}"  class="{{ Request::is('Receip/create') ? 'text-blue-700 border-blue-700 text-lg border-2' : 'text-gray-600' }} text-sm  py-2   px-2   leading-none rounded-md hover:bg-gray-100" > سند قبض جديد</a>
            </li>
            <li class="    ">
                <a href="{{route('show_all_receipt')}}"  class="{{ Request::is('Receip/show_all_receipt') ? 'text-blue-700 border-blue-700 text-lg border-2' : 'text-gray-600' }} text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" > المقبوضات</a>

            </li>
            <li class="   "> 
                <a href="{{route('exchange.index')}}"  class="{{ Request::is('exchange/index') ? 'text-blue-700 border-blue-700 text-lg border-2' : 'text-gray-600' }} text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" > سند صرف جديد </a>

            </li>
            <li class="     ">  
                <a href="{{route('all_exchange_bonds')}}" id="Accountbalancing"  class="{{ Request::is('exchange/all_exchange_bonds') ? 'text-blue-700 border-blue-700 text-lg border-2' : 'text-gray-600' }} text-sm py-2 px-2  rounded-md hover:bg-gray-100" >  المدفوعات</a>
            </li>

        </ul>
    
        <div class=" container relative  ">
            <button onclick="window.history.back()">رجوع</button>
     
    @yield('bonds')
</div>

<script>
  
</script>
@endsection
