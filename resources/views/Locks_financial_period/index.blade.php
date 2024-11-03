@extends('layout')
@section('conm')
{{-- <form action="{{route(locks_financial_period.index)}}" method="GET" ></form>
<div id="reportContainer" class="mt-6 ">
    <div class="mb-6">
        <h2 class="text-lg font-semibold">الإيرادات والمصروفات</h2>
        <table class="min-w-full border bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">البند</th>
                    <th class="py-3 px-6 text-right">المبلغ</th>
                </tr>
            </thead>
            <tbody id="revenueExpenses">
                <!-- البيانات سيتم تحميلها عبر AJAX -->
            </tbody>
        </table>
    </div> --}}
<div class="container mx-auto my-8">
    <h1 class="text-2xl font-bold mb-4">تقرير الأرباح والخسائر</h1>

    <button id="loadReport" class="bg-blue-500 text-white py-2 px-4 rounded">تحميل التقرير</button>

    <div id="reportContainer" class="mt-6 ">
        <div class="mb-6">
            <h2 class="text-lg font-semibold">الإيرادات والمصروفات</h2>
            <table class="min-w-full border bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">البند</th>
                        <th class="py-3 px-6 text-right">المبلغ</th>
                    </tr>
                </thead>
                <tbody id="revenueExpenses">
                    <!-- البيانات سيتم تحميلها عبر AJAX -->
                </tbody>
            </table>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold">الأصول</h2>
            <table class="min-w-full border bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">الحساب</th>
                        <th class="py-3 px-6 text-center">نوع الحساب</th>
                        <th class="py-3 px-6 text-right">الرصيد</th>
                    </tr>
                </thead>
                <tbody id="assetsTable">
                    <!-- البيانات سيتم تحميلها عبر AJAX -->
                </tbody>
            </table>
        </div>
        
        <div class="mb-6">
            <h2 class="text-lg font-semibold">الالتزامات</h2>
            <table class="min-w-full border bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">الحساب</th>
                        <th class="py-3 px-6 text-center">نوع الحساب</th>
                        <th class="py-3 px-6 text-right">الرصيد</th>
                    </tr>
                </thead>
                <tbody id="liabilitiesTable">
                    <!-- البيانات سيتم تحميلها عبر AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#loadReport').on('click', function() {
        $.ajax({
            url: '{{ route("Locks_financial_period.getProfitAndLossData") }}',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // عرض الإيرادات والمصروفات
                $('#revenueExpenses').html(`
                    <tr class="border-b">
                        <td class="py-3 px-6">إجمالي الإيرادات</td>
                        <td class="py-3 px-6 text-right">${data.totalRevenue} ريال</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-3 px-6">إجمالي المصروفات</td>
                        <td class="py-3 px-6 text-right">${data.totalExpenses} ريال</td>
                    </tr>
                    <tr class="font-bold">
                        <td class="py-3 px-6">صافي الربح/الخسارة</td>
                        <td class="py-3 px-6 text-right">${data.netProfitOrLoss} ريال</td>
                    </tr>
                `);

                // عرض الأصول
                let assetsHTML = '';
                $.each(data.assets, function(index, asset) {
                    assetsHTML += `
                       <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">${account.name}</td>
                    <td class="py-3 px-6 text-center">${account.type}</td>
                    <td class="py-3 px-6 text-right">${account.balance}</td>
                </tr>`;
                });
                $('#assetsTable').html(assetsHTML);

                // عرض الالتزامات
                let liabilitiesHTML = '';
                $.each(data.liabilities, function(index, liability) {
                    liabilitiesHTML += `
                         <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">${account.name}</td>
                    <td class="py-3 px-6 text-center">${account.type}</td>
                    <td class="py-3 px-6 text-right">${account.balance}</td>
                </tr>`
                });
                $('#liabilitiesTable').html(liabilitiesHTML);

                // إظهار التقرير
                $('#reportContainer').removeClass('hidden');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    });
});
</script>
@endsection
