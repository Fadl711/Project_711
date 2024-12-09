<nav class=" shadow-md bg-white ">
    <div class="flex ">
        <a href="{{route('show_inventory')}}"  class="py-2 px-4 {{ Request::is('inventory/show_inventory') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50" >  قائمة الجرد   </a>
        <a href="{{route('inventory.create')}}"   class="py-2 px-4 {{ Request::is('inventory/create') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50" >  انشاء جرد </a>
      </div>
</nav>
    {{-- <a href="{{route('inventory.create')}}"  class="text-sm  py-2   px-2   leading-none rounded-md hover:bg-gray-100" >  انشاء قائمة جرد </a> --}}
