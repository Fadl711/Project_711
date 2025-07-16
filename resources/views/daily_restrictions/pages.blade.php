@extends('daily_restrictions.index')
@section('restrictions')
    <div class="flex justify-between items-center mb-4 px-4 gap-2">
        <div class="w-full flex items-center gap-2">
            <div class="relative w-1/2">
                <input id="searchDate" type="date" class="border border-gray-300 rounded px-3 py-2 text-sm w-full pr-10" />

                <!-- زر الإلغاء (❌) -->
                <button id="clearDateBtn" style="display: none" type="button"
                    class="absolute left-10 top-1/2 -translate-y-1/2 text-gray-500 hover:text-red-600 focus:outline-none">
                    ❌
                </button>
            </div>
            <button id="searchBtn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                🔍 بحث
            </button>

        </div>

        <button id="sortBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            ترتيب تصاعدي 🔼
        </button>
    </div>

    <div class="w-full overflow-y-auto max-h-[80vh]  bg-white">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        رقم الصفحة
                    </th>
                    <th scope="col" class="px-6 py-3">
                        تاريخ انشاء الصفحة
                    </th>
                    <th scope="col" class="px-6 py-3">
                        عدد القيود
                    </th>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                </tr>
            </thead>
            <tbody id="restrictionsBody">
                @foreach ($pagesNum as $pageNum)
                    <tr
                        class="bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <a href="{{ route('all_restrictions_show', $pageNum->page_id) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $pageNum->page_id }}</a>

                        </th>
                        @php

                            $date = $pageNum->created_at;
                            $carbonDate = \Carbon\Carbon::parse($date);

                            $arabicDay = $carbonDate->format('l'); // Get the day of the week in English
                            $arabicDays = [
                                'Sunday' => 'الأحد',
                                'Monday' => 'الإثنين',
                                'Tuesday' => 'الثلاثاء',
                                'Wednesday' => 'الأربعاء',
                                'Thursday' => 'الخميس',
                                'Friday' => 'الجمعة',
                                'Saturday' => 'السبت',
                            ];
                            $dateOnly = \Carbon\Carbon::parse($pageNum->created_at)->format('Y-m-d');
                        @endphp
                        <td class="px-6 py-4" data-date="{{ $dateOnly }}">
                            {{ $dateOnly . ' : ' . $arabicDays[$arabicDay] }}
                        </td>
                        <td class="px-6 py-4 ">
                            <div class="bg-green-500/70 h-7 w-7 pt-1 rounded-full text-center text-white">
                                {{ $pageNum->dailyEntries->count() }}</div>
                        </td>
                        <td class="px-6 py-4 ">
                            <a href="{{ route('all_restrictions_show', $pageNum->page_id) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">اظهار</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById("searchDate");
            const searchBtn = document.getElementById("searchBtn");
            const clearBtn = document.getElementById("clearDateBtn");
            const sortBtn = document.getElementById("sortBtn");
            const tableBody = document.getElementById("restrictionsBody");
            let sortAsc = false;

            // 🔍 بحث
            searchBtn.addEventListener("click", () => {
                const selectedDate = searchInput.value; // "2025-07-14"
                clearBtn.style.display = selectedDate ? "inline" : "none";

                Array.from(tableBody.querySelectorAll("tr")).forEach(row => {
                    const rowDate = row.children[1].dataset.date; // الآن موجود فعلاً
                    row.style.display = (rowDate === selectedDate) ? "" : "none";
                });
            });

            // ❌ إلغاء البحث
            clearBtn.addEventListener("click", () => {
                searchInput.value = "";
                Array.from(tableBody.rows).forEach(r => r.style.display = "");
                clearBtn.style.display = "none";
            });

            // 🔁 ترتيب الصفوف
            sortBtn.addEventListener("click", () => {
                sortAsc = !sortAsc;
                const rows = Array.from(tableBody.querySelectorAll("tr"));

                rows.sort((a, b) => {
                    const aId = parseInt(a.cells[0].textContent, 10) || 0;
                    const bId = parseInt(b.cells[0].textContent, 10) || 0;
                    return sortAsc ? aId - bId : bId - aId;
                });

                sortBtn.textContent = sortAsc ? "ترتيب تنازلي 🔽" : "ترتيب تصاعدي 🔼";
                tableBody.innerHTML = "";
                rows.forEach(r => tableBody.appendChild(r));
            });
        });
    </script>
@endsection
