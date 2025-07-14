<nav class=" shadow-md bg-white ">
    <div
        class="flex justify-evenly  text-sm  2xl:w-full items-center px-4 p-2  bg-gradient-to-t text-white from-indigo-900 to-indigo-600 rounded-md shadow-sm font-medium capitalize hover:text-blue-600">
        <a href="{{ route('production_system.dashboard') }}"
            class="ajax-link py-2 px-4 {{ Request::is('production-system/dashboard') ? '   border-b-2  font-bold text-xl pointer-events-none opacity-50' : 'border-b-0' }} border-white hover:text-blue-600">
            خطوط الإنتاج</a>
        <a href="{{ route('production-stages.index') }}"
            class="ajax-link py-2 px-4 {{ Request::is('production-stages/index') ? '   border-b-2  font-bold text-xl pointer-events-none opacity-50' : 'border-b-0' }} border-white hover:text-blue-600">
            مراحل الإنتاج</a>
        <a href="{{ route('production_orders.index') }}"
            class="ajax-link py-2 px-4 {{ Request::is('production_orders/index') ? '   border-b-2  font-bold text-xl pointer-events-none opacity-50' : 'border-b-0' }} border-white hover:text-blue-600">
            أوامر الإنتاج
        </a>
        <a href="{{ route('product-boms.index') }}"
            class="ajax-link py-2 px-4 {{ Request::is('product-boms/index') ? '   border-b-2  font-bold text-xl pointer-events-none opacity-50' : 'border-b-0' }} border-white hover:text-blue-600">
            مكونات المنتج
        </a>
        <a href="{{ route('equipment-maintenance.index') }}"
            class="ajax-link py-2 px-4 {{ Request::is('equipment-maintenance/index') ? 'border-b-2 font-bold text-xl pointer-events-none opacity-50' : 'border-b-0' }} border-white hover:text-blue-600 ">
            إدارة الصيانة
        </a>

    </div>
</nav>
