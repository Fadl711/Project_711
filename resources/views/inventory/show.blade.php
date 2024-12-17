@extends('inventory.index')

@section('inventory')
<style>
    #dropdownDots {
    display: none; /* إخفاء القائمة بشكل افتراضي */
    opacity: 0; /* تعيين الشفافية إلى 0 */
    transition: opacity 0.3s ease; /* تأثير الانتقال */
}

#dropdownDots:not(.hidden) {
    display: block; /* عرض القائمة */
    opacity: 1; /* تعيين الشفافية إلى 1 */
}

/* إضافة قاعدة لجعل القائمة تظهر تحت الزر */
.relative {
    position: relative; /* تأكد من أن العنصر الأب له وضع نسبي */
}

.absolute {
    position: absolute; /* تأكد من أن القائمة لها وضع مطلق */
    top: 100%; /* جعل القائمة تظهر أسفل الزر */
    left: 0; /* محاذاة القائمة إلى اليسار */
    z-index: 10; /* تأكد من أن القائمة فوق العناصر الأخرى */
}
</style>
<div class="flex flex-col justify-center items-center mb-6">
    <div class="relative border text-black border-gray-200 rounded-lg w-[50%] mb-4">
        <input type="text" class="rounded-md w-full text-left py-2 px-3" placeholder="Search">
        <button type="submit" class="absolute right-6 top-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </button>
    </div>


<div class="min-w-full shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full bg-white text-sm">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="py-2 text-right">رقم الجرد</th>
                <th class="py-2 text-right">اسم المخزن</th>
                <th class="py-2 text-right">عنوان الجرد</th>
                <th class="py-2 text-right">مسؤول الجرد</th>
                <th class="py-2 text-right">المستخدم</th>
                <th class="py-2 text-right">تاريخ الجرد</th>
                <th class="py-2 text-right">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventorys as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 text-right">{{ $item['id'] }}</td>
                    <td class="py-2 text-right">{{ $item['StoreId'] }}</td>
                    <td class="py-2 text-right text-[#2430d3]">{{ $item['InventoryTitle'] }}</td>
                    <td class="py-2 text-right">{{ $item['employee'] ?? 'غير معروف' }}</td>
                    <td class="py-2 text-right">{{ $item['User_id'] }}</td>
                    <td class="py-2 text-right">{{ optional($item['created_at'])->format('Y-m-d') }}</td>
                    <td class="py-2 text-right relative">
                        <button id="dropdownMenuIconButton" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none" type="button">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdownDots" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 absolute mt-1">
                            <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownMenuIconButton">
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">عرض</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">فارق الجرد للكميات الناقصة</a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">فارق الجرد للكميات الزائدة</a>
                                </li>
                            </ul>
                            <div class="py-2">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Separated link</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButton = document.getElementById('dropdownMenuIconButton');
        const dropdownMenu = document.getElementById('dropdownDots');
    
        // تفعيل القائمة المنسدلة عند الضغط على الزر
        dropdownButton.addEventListener('click', function(event) {
            event.stopPropagation(); // منع الحدث من الانتقال إلى عناصر أخرى
            dropdownMenu.classList.toggle('hidden'); // تبديل عرض القائمة
        });
        // إغلاق القائمة عند النقر في أي مكان آخر
        document.addEventListener('click', function(event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden'); // إخفاء القائمة
            }
        });
    });
    </script>
@endsection