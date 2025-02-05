<nav>
    <ul class="grid grid-cols-5 text-right container relative shadow-md px-2 py-2 bg-gradient-to-t text-white from-indigo-900 to-indigo-600 rounded-md  font-medium capitalize hover:text-blue-600">
        <li class="rounded">
            <a href="{{ route('restrictions.create') }}"
            class="py-2 px-4 {{ Route::is('restrictions.create') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600"
            >
           إنشاء قيد 
        </a>
        </li>
        <li class="rounded">
            <a href="{{ route('all_restrictions_show_1') }}"class="py-2 px-4 {{ Route::is('all_restrictions_show_1') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
            القيود
        </a>
    </li>
    <li class="rounded">
        <a href="{{ route('transfer_restrictions.create') }}"
        class="py-2 px-4 {{ Route::is('transfer_restrictions.create') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
                الترحيل القيود
            </a>
        </li>
        <li class="rounded">
            <a href="{{ route('general_entries.show') }}"
            class="py-2 px-4 {{ Route::is('general_entries.show') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
            القيود المرحلة
                </a>
            </li>
        <li class="rounded">
            <a href="{{ route('transfer_restrictions.record') }}"
            class="py-2 px-4 {{ Route::is('transfer_restrictions.record') ? 'border-b-2 font-bold text-xl' : 'border-b-0' }} border-white hover:text-blue-600">
            السجلات السنوية
        </a>
    </li>
    </ul>
</nav>
<br>
