<nav class=" shadow-md bg-white ">
    <ul class="grid grid-cols-4 text-right container relative shadow-md px-2 py-2 bg-gradient-to-t text-white from-indigo-900 to-indigo-600 rounded-md  font-medium capitalize hover:text-blue-600">
        <li class="rounded">
            <a href="{{ route('inventory.create') }}"
            class="py-2 px-4 {{ Route::is('inventory.create') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
            انشاء جرد
            </a>
        </li>
        <li class="rounded">
            <a href="{{ route('inventory.createList') }}"
            class="py-2 px-4 {{ Route::is('inventory.createList') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
            انشاء جرد
            </a>
        </li>
        <li class="rounded">
            <a href="{{ route('accountingPeriod') }}"
            class="py-2 px-4 {{ Route::is('accountingPeriod') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
            انشاء جرد
            </a>
        </li>
</ul>
</nav>
<br>
