@extends('inventory.index')

@section('inventory')
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

    {{-- <!-- زر تحميل CSV -->
    <div class="mb-4">
        <a href="{{ route('inventory.export') }}" class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600">
            تحميل بيانات الجرد بتنسيق CSV
        </a>
    </div>
</div> --}}

<div class="min-w-full shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full bg-white text-sm">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="py-2 text-right">رقم الفتره</th>
                <th class="py-2 text-right">تاريخ فتره الجرد</th>
                {{-- <th class="py-2 text-right">عنوان الجرد</th> --}}
                {{-- <th class="py-2 text-right">مسؤول الجرد</th> --}}
                {{-- <th class="py-2 text-right">المستخدم</th> --}}
                {{-- <th class="py-2 text-right">تاريخ الجرد</th> --}}
                <th class="py-2 text-right">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accountingPeriod as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2 text-right">{{ $item->accounting_period_id }}</td>
                    <td class="py-2 text-right">{{ $item->created_at }}</td>
                    {{-- <td class="py-2 text-right text-[#2430d3]">{{ $item['InventoryTitle'] }}</td>
                    <td class="py-2 text-right">{{ $item['employee'] ?? 'غير معروف' }}</td>
                    <td class="py-2 text-right">{{ $item['User_id'] }}</td>
                    <td class="py-2 text-right">{{ optional($item['created_at'])->format('Y-m-d') }}</td> --}}
                    <td class="py-2 text-right">
                        <a href="{{ route('inventory.show', $item->accounting_period_id) }}" class="text-[#2430d3] hover:underline">
                            <svg class="w-6 h-6 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection