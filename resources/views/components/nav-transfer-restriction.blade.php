<nav>
<ul class="grid grid-cols-4 text-right container relative shadow-md px-2 py-2  bg-white">
    
    <li class="   rounded ">
        <a href="{{route('transfer_restrictions.record')}}"  class="text-sm py-2 px-2 {{ Request::is('transfer_restrictions/record') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50"> السجلات السنوية</a>

    </li>
    <li class="   rounded ">
        <a href="{{route('transfer_restrictions.create')}}"   class="text-sm py-2 px-2 {{ Request::is('transfer_restrictions/create') ? '   border-b-2   text-[#0a0aeec6]' : 'border-b-0' }} border-[#0a0aeec6]   leading-none rounded hover:bg-gray-50"> الترحيل القيود</a>
    </li>

</ul>
</nav>
