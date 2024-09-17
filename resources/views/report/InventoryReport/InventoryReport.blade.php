@extends('report.layout')
@section('report')

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-200 text-center">
                        <tr>
                            <th class="py-2 px-4 border-b">المخزن</th>
                            <th class="py-2 px-4 border-b">اسم الصنف</th>
                            <th class="py-2 px-4 border-b">كمية واردة</th>
                            <th class="py-2 px-4 border-b">كمية منصرف</th>
                            <th class="py-2 px-4 border-b">قيمة الوارد</th>
                            <th class="py-2 px-4 border-b">قيمة المنصرف</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr>
                            <td class="py-2 px-4 border-b text-center">المخزن الرئيسي</td>
                            <td class="py-2 px-4 border-b text-center">لوج شمسي</td>
                            <td class="py-2 px-4 border-b text-center">0</td>
                            <td class="py-2 px-4 border-b text-center">2</td>
                            <td class="py-2 px-4 border-b text-center">0</td>
                            <td class="py-2 px-4 border-b text-center">20000</td>
                        </tr>
                    </tbody>
                </table>
            </div>


@endsection
