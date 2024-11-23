<nav class=" shadow-md bg-white ">
    <div class="flex ">
        <a href="{{route('customers.show')}}"  class="py-2 px-4 {{ Request::is('customers/show') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50" > بيانات العملاء  </a>
        <a href="{{route('customers.create')}}"   class="py-2 px-4 {{ Request::is('customers/create') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50" >اضافة  عميل</a>
        
      </div>
</nav>
