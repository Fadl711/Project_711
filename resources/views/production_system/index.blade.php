
@extends('layout')
@section('conm')
<x-nav-production-system/>

 {{-- <li class="  rounded ">
                <a href="#}"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" > {{$today}}</a>

            </li> --}}
                       {{-- <li class="    rounded ">
                <a href="{{route('all_exchange_bonds')}}" id="Accountbalancing"  class="text-sm py-2 px-2  rounded-md hover:bg-gray-100" >  المدفوعات</a>
            </li> --}}



{{--             <button onclick='window.location.replace("/home");'>رجوع</button>
 --}}
    @yield('productionSystem')


<script>

</script>
@endsection
