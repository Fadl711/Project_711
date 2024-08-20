@extends('layout')

@section('conm')

<h2 class="text-2xl font-bold  text-right my-3 underline underline-offset-4"> الأصول الثابتة</h2>

<div class="block w-1/2    border">
    <table class="items-center w-full bg-transparent border-collapse">
        <thead>
            <tr>
                <th
                    class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">
                    اسم الأصل</th>
                <th
                    class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">
                    تنصيف الأصل </th>
                <th
                    class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">
                    سعر الشراء </th>
                <th
                    class="px-4 bg-gray-50 text-gray-700 align-middle py-3 text-xs font-semibold text-left uppercase border-l-0 border-r-0 whitespace-nowrap">
                    تاريخ الشراء </th>
                <th
                    class="px-4 bg-gray-50 text-gray-700 text-center align-middle py-3 text-xs font-semibold  uppercase border-l-0 border-r-0 whitespace-nowrap min-w-140-px">
                    الهلاك
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
{{-- @for ($x = 0 ; $x<3 ; $x++) --}}

<tr class="text-gray-500">
    <th class="border-t-0 px-4 align-middle text-sm font-normal whitespace-nowrap p-4 text-left">كمبيوتر</th>
    <td class="border-t-0 px-4 align-middle text-xs font-medium text-gray-900 whitespace-nowrap ">
        <select class="text-sm rounded-lg" name="" id="">
            <option value="">إكترونيات</option>
            <option value="">اجهزات</option>
        </select></td>
    <td class="border-t-0 px-4 align-middle text-xs font-medium text-gray-900 whitespace-nowrap p-4">
        5,649</td>
    <td class="border-t-0 px-4 align-middle text-xs font-medium text-gray-900 whitespace-nowrap p-4">
        2224/4/12</td>
    <td class="border-t-0 px-4 align-middle text-xs whitespace-nowrap p-4">
        <div class="flex items-center">
            <span class="mr-2 text-xs font-medium">{{11}}%</span>
            <div class="relative w-full">
                <div class="w-full bg-gray-200 rounded-sm h-2">
                    <div class="bg-cyan-600 h-2 rounded-sm" style="width: 3{{$x}}%"></div>
                </div>
            </div>
        </div>
    </td>
</tr>
{{-- @endfor --}}

        </tbody>
    </table>
</div>

@endsection
