<nav class="w-full px-2 py-1">
    <ul
        class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2
               container mx-auto relative shadow rounded
               bg-gradient-to-t from-indigo-900 to-indigo-600
                text-right">

        <li class="text-center lg:text-right">
            <a href="{{ route('restrictions.create') }}"
                class="block py-3 px-4 text-white hover:bg-indigo-700
                      transition-colors duration-200
                      text-sm md:text-base
                      {{ Route::is('restrictions.create') ? 'border-b-2 font-bold' : 'border-b-0' }}
                      border-white">
                إنشاء قيد
            </a>
        </li>

        <li class="text-center lg:text-right">
            <a href="{{ route('restrictions.pages') }}"
                class="block py-3 px-4 text-white hover:bg-indigo-700
                      transition-colors duration-200
                      text-sm md:text-base
                      {{ Route::is('restrictions.pages') ? 'border-b-2 font-bold' : 'border-b-0' }}
                      border-white">
                القيود
            </a>
        </li>
        <li class="text-center lg:text-right">
            <a href="{{ route('double_entries.create') }}"
                class="block py-3 px-4 text-white hover:bg-indigo-700
                      transition-colors duration-200
                      text-sm md:text-base
                      {{ Route::is('double_entries.create') ? 'border-b-2 font-bold' : 'border-b-0' }}
                      border-white">
                قيد مزدوج
            </a>
        </li>
        <li class="text-center lg:text-right">
            <a href="{{ route('restrictions.createCurrency') }}"
                class="block py-3 px-4 text-white hover:bg-indigo-700
                      transition-colors duration-200
                      text-sm md:text-base
                      {{ Route::is('restrictions.createCurrency') ? 'border-b-2 font-bold' : 'border-b-0' }}
                      border-white">
                قيود العملات
            </a>
        </li>
        <li class="text-center lg:text-right">
            <a href="{{ route('transfer_restrictions.create') }}"
                class="block py-3 px-4 text-white hover:bg-indigo-700
                      transition-colors duration-200
                      text-sm md:text-base
                      {{ Route::is('transfer_restrictions.create') ? 'border-b-2 font-bold' : 'border-b-0' }}
                      border-white">
                ترحيل القيود
            </a>
        </li>


        <li class="text-center lg:text-right">
            <a href="{{ route('general_entries.show') }}"
                class="block py-3 px-4 text-white hover:bg-indigo-700
                      transition-colors duration-200
                      text-sm md:text-base
                      {{ Route::is('general_entries.show') ? 'border-b-2 font-bold' : 'border-b-0' }}
                      border-white">
                القيود المرحلة
            </a>
        </li>

        <li class="text-center lg:text-right">
            <a href="{{ route('transfer_restrictions.record') }}"
                class="block py-3 px-4 text-white hover:bg-indigo-700
                      transition-colors duration-200
                      text-sm md:text-base
                      {{ Route::is('transfer_restrictions.record') ? 'border-b-2 font-bold' : 'border-b-0' }}
                      border-white">
                السجلات السنوية
            </a>
        </li>
    </ul>
</nav>
<br>
