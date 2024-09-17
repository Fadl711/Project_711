@extends('report.layout')
@section('report')

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-200 text-center">
                        <tr>
                            <th class="py-2 px-4 border-b">رقم الحساب</th>
                            <th class="py-2 px-4 border-b">اسم الحساب</th>
                            <th class="py-2 px-4 border-b">مدين/عليه</th>
                            <th class="py-2 px-4 border-b">دائن/له</th>
                            <th class="py-2 px-4 border-b">الرصيد</th>
                        </tr>
                    </thead>
                    <tbody cl>
                        <tr>
                            <td class="py-2 px-4 border-b text-center">1</td>
                            <td class="py-2 px-4 border-b text-center">الصندوق الرئيسي </td>
                            <td class="py-2 px-4 border-b text-center">1000</td>
                            <td class="py-2 px-4 border-b text-center">500</td>
                            <td class="py-2 px-4 border-b text-center">500</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b text-center">2</td>
                            <td class="py-2 px-4 border-b text-center">حساب المخزون</td>
                            <td class="py-2 px-4 border-b text-center">2000</td>
                            <td class="py-2 px-4 border-b text-center">1500</td>
                            <td class="py-2 px-4 border-b text-center">500</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b text-center">2</td>
                            <td class="py-2 px-4 border-b text-center">حساب المبيعات</td>
                            <td class="py-2 px-4 border-b text-center">2000</td>
                            <td class="py-2 px-4 border-b text-center">1500</td>
                            <td class="py-2 px-4 border-b text-center">500</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b text-center">2</td>
                            <td class="py-2 px-4 border-b text-center">حساب المشتريات</td>
                            <td class="py-2 px-4 border-b text-center">2000</td>
                            <td class="py-2 px-4 border-b text-center">1500</td>
                            <td class="py-2 px-4 border-b text-center">500</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b text-center">2</td>
                            <td class="py-2 px-4 border-b text-center">حساب راس المال</td>
                            <td class="py-2 px-4 border-b text-center">2000</td>
                            <td class="py-2 px-4 border-b text-center">1500</td>
                            <td class="py-2 px-4 border-b text-center">500</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b text-center">2</td>
                            <td class="py-2 px-4 border-b text-center">حساب الأرباح والخسائر </td>
                            <td class="py-2 px-4 border-b text-center">2000</td>
                            <td class="py-2 px-4 border-b text-center">1500</td>
                            <td class="py-2 px-4 border-b text-center">500</td>
                        </tr>
                        <!-- يمكنك إضافة المزيد من الصفوف هنا -->
                    </tbody>
                </table>
            </div>

@endsection
