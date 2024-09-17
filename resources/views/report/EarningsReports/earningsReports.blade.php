@extends('report.layout')
@section('report')

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-200 text-center">
                        <tr>
                            <th class="py-2 px-4 border-b">رقم الصنف</th>
                            <th class="py-2 px-4 border-b">اسم الصنف</th>
                            <th class="py-2 px-4 border-b">كمية مباعة</th>
                            <th class="py-2 px-4 border-b">إجمالي المبيعات</th>
                            <th class="py-2 px-4 border-b">كمية مرتجع</th>
                            <th class="py-2 px-4 border-b">أجمالي المرتجع</th>
                            <th class="py-2 px-4 border-b"> مبلغ الخصم</th>
                            <th class="py-2 px-4 border-b">أجمالي التكلفة</th>
                            <th class="py-2 px-4 border-b">صافي المبيعات</th>
                            <th class="py-2 px-4 border-b">أجمالي الربح</th>
                            <th class="py-2 px-4 border-b">نسبة الربح</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr>
                            <td class="py-2 px-4 border-b text-center">1</td>
                            <td class="py-2 px-4 border-b text-center">لوج شمسي</td>
                            <td class="py-2 px-4 border-b text-center">2</td>
                            <td class="py-2 px-4 border-b text-center">20000</td>
                            <td class="py-2 px-4 border-b text-center">0</td>
                            <td class="py-2 px-4 border-b text-center">0</td>
                            <td class="py-2 px-4 border-b text-center">0</td>
                            <td class="py-2 px-4 border-b text-center">15000</td>
                            <td class="py-2 px-4 border-b text-center">20000</td>
                            <td class="py-2 px-4 border-b text-center">5000</td>
                            <td class="py-2 px-4 border-b text-center">33%</td>
                        </tr>
                    </tbody>
                </table>
            </div>


@endsection
