@extends('report.layout')
@section('report')

@section('nav')
                        <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600 px-2">
                            <div class="flex">
                                <div class="flex items-center me-4">
                                    <input id="inline-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="inline-radio" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> المبيعات الأجلة</label>
                                </div>
                                <div class="flex items-center me-4">
                                    <input id="inline-2-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="inline-2-radio" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">المبيعات النقديه</label>
                                </div>
                                <div class="flex items-center me-4">
                                    <input checked id="inline-checked-radio" type="radio" value="" name="inline-radio-group" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="inline-checked-radio" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">كلاهما </label>
                                </div>
                            </div>
                        </li>
@endsection




            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-200 text-center">
                        <tr>
                            <th class="py-2 px-4 border-b">رقم الصنف</th>
                            <th class="py-2 px-4 border-b">اسم الصنف</th>
                            <th class="py-2 px-4 border-b">الوحدة</th>
                            <th class="py-2 px-4 border-b">سعر الوحدة</th>
                            <th class="py-2 px-4 border-b">الكمية</th>
                            <th class="py-2 px-4 border-b">الأجمالي</th>
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($SaleInvoice as $item)

                        <tr>
                            <td class="py-2 px-4 border-b text-center">1</td>
                            <td class="py-2 px-4 border-b text-center">سلك كهرباء</td>
                            <td class="py-2 px-4 border-b text-center">متر</td>
                            <td class="py-2 px-4 border-b text-center">1000</td>
                            <td class="py-2 px-4 border-b text-center">3</td>
                            <td class="py-2 px-4 border-b text-center">3000</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>


@endsection
