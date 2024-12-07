@extends('layout')

@section('conm')
<x-nav-customer/>

<div class="container mx-auto mt-10">
    <h2 class="text-lg font-bold mb-4">رصيد العملاء</h2>
    <table class="w-full text-sm text-right border-collapse border border-gray-200 shadow-md">
        <thead class="bg-blue-100 text-gray-700">
            <tr>
                <th class="px-4 py-2 border border-gray-300">#</th>
                <th class="px-4 py-2 border border-gray-300">اسم العميل</th>
                <th class="px-4 py-2 border border-gray-300">الهاتف</th>
                <th class="px-4 py-2 border border-gray-300">إجمالي المدين</th>
                <th class="px-4 py-2 border border-gray-300">إجمالي الدائن</th>
                <th class="px-4 py-2 border border-gray-300">الفارق</th>
                <th class="px-4 py-2 border border-gray-300">نوع الفارق</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @foreach ($balances as $index => $balance)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border border-gray-300">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $balance->sub_name }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $balance->Phone }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ number_format($balance->total_debit, 2) }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ number_format($balance->total_credit, 2) }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ number_format(abs($balance->difference), 2) }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $balance->difference_type }}</td>
                   
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection