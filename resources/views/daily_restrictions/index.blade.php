
@extends('layout')
@section('conm')

        <ul class="grid grid-cols-4 text-right container relative shadow-md px-2 py-2  bg-white">
            <li class="  rounded ">

                <a href="{{route('restrictions.create')}}"  class="text-sm  py-2   px-2   leading-none rounded-md hover:bg-gray-100" >   قيد جديد </a>
            </li>
            <li class="   rounded ">
                <a href="{{route('all_restrictions_show_1')}}"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" > القيود</a>

            </li>
            <li class="   rounded ">
                <a href="{{route('transfer_restrictions.create')}}"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" > الترحيل القيود</a>
            </li>
 {{-- <li class="  rounded ">
                <a href="#}"  class="text-sm py-2 px-2  leading-none rounded-md hover:bg-gray-100" > {{$today}}</a>

            </li> --}}
                       {{-- <li class="    rounded ">
                <a href="{{route('all_exchange_bonds')}}" id="Accountbalancing"  class="text-sm py-2 px-2  rounded-md hover:bg-gray-100" >  المدفوعات</a>
            </li> --}}

        </ul>

        <div class=" container relative  ">
            <button onclick='window.location.replace("/home");'>رجوع</button>

    @yield('restrictions')
</div>

<script>

</script>
@endsection
