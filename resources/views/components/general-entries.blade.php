<style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
        .header-section {
            background-color: #f3f4f6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        table th, table td {
            text-align: center;
        }
        .no-print button {
            transition: background-color 0.3s ease;
        }
        .no-print button:hover {
            transform: scale(1.05);
        }
        @media print {
        .no-print {
            display: none;
        }
        
    }
    body {
        
        font-family: Arial, sans-serif; /* الخط الافتراضي */
    }
  
</style>
{{-- @props(['general_entries']) --}}
<div class=" overflow-x-auto bg-white shadow-md sm:rounded-lg    w-full px-4 py-2   max-h-full">
  
    <table class="text-sm   font-semibold w-full overflow-y-auto max-h-[80vh] border-collapse">
        <thead class="bg-gray-100 sticky top-0  uppercase dark:bg-gray-700 dark:text-gray-400">
        <tr class="">
        <th class="rounded-s-lg">ID</th>
        <th class="">رقم الفرعي </th>
        {{-- <th class="">رقم الرئيسي</th> --}}
        <th class="">رقم القيد </th>
        <th class="">رقم الصفحة </th>
        {{-- <th class=""> المستخدم</th> --}}
        {{-- <th class="">رقم الصفحة العامة</th> --}}
        <th class=""> الفترة </th>
        {{-- <th class="">نوع القيد</th> --}}
        <th class="">المبلغ</th>
        <th class=""> العملة</th>
        <th class="">نوع المستند</th>
        <th class="">رقم المستند</th>
        <th class="">الوصف</th>
        <th class="">تاريخ القيد</th>
        <th class=" text-center rounded-e-lg py-1">تحقق من القيد</th>
    </tr>
</thead>
<tbody>

    @foreach($general_entries as $entry)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
            <td>{{ $entry['id'] }}</td>
<td>{{ $entry['subAccount'] ? $entry['subAccount']->sub_name : 'غير محدد' }}/{{ $entry['entry_type'] }}</td>
            {{-- <td>{{ $entry['Main_id'] }}</td> --}}
            <td>
                <a href="{{ route('restrictions.show', $entry['daily_entry_id'] ) }}" class="text-sm py-2 leading-none rounded-md hover:bg-gray-100">
                   {{ $entry['daily_entry_id'] }}
                </a>
            </td>
            <td>{{ $entry['daily_Page_id'] }}</td>
            {{-- <td>{{ $entry['user_id'] ? $entry['User_id']->name : 'غير محدد' }}</td> --}}
            {{-- <td>{{ $entry['general_ledger_page_number_id'] }}</td> --}}
            <td>{{ $entry['accounting_period_id'] }}</td>
            {{-- <td>{{ $entry['entry_type'] }}</td> --}}
            <td>{{ $entry['amount'] }}</td>
            <td>{{ $entry['currency_name'] }}</td>
            <td>{{ $entry['invoice_type'] }}</td>
            <td>{{ $entry['invoice_id'] }}</td>
            <td>{{ $entry['description'] }}</td>
            <td>{{ $entry['entry_date'] }}</td>
            <td class=" text-center" >
                @isset($entry['entrie_id'])
                    
                <div class="">
                    <span class="  items-center  w-6 h-6 bg-green-200 rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-green-900">
                        <svg class="w-3 h-3 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                        </svg>
                     {{$entry['entrie_id']}}
                    </span>
                    </div>
                    @endisset
                    @if (!$entry['entrie_id'])
                <div id="tooltip-default" >
                    <svg class="w-3 h-3 text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>

                </div>
                  @endif
                 

            </td>
        </tr>
    @endforeach
</tbody>
</table>
</div>