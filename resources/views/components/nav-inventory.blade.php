<nav class=" shadow-md bg-white ">
    <div class="flex ">
      <a href="{{route('inventory.create')}}"   class="py-2 px-4 {{ Request::is('inventory/create') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50" >  انشاء جرد </a>
      <a href="{{route('inventory.createList')}}"   class="py-2 px-4 {{ Request::is('inventory/Create-an-inventory-list') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50" >   تقارير الجرد </a>
      <a href="{{route('accountingPeriod')}}"  class="py-2 px-4 {{ Request::is('inventory/accountingPeriod') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50" >  قائمة الجرد   </a>
      </div>
</nav>
