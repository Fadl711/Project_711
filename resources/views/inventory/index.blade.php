@extends('layout')

@section('conm')







<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">



    <h2 class="text-2xl font-bold  text-right my-3 underline underline-offset-4"> قائمة الجرد</h2>
    <form class="flex flex-col md:flex-row gap-3 mb-5">
        <div class="flex">
            <input type="text" placeholder="ابحث عن الصنف الذي تريد جرده"
                class="w-full md:w-80 px-3 h-10 rounded-r border-2 border-bro und focus:outline-none focus:border-bro/80"
                >
            <button type="submit" class="bg-bro text-white rounded-l  px-2 md:px-3 py-0 md:py-1 ">ابحث</button>
        </div>
        <select id="pricingType" name="pricingType"
            class="text-left w-40 h-10 border-2 border-bro focus:outline-none focus:border-sky-500 text-bro rounded px-2 md:px-3 py-0 md:py-1 tracking-wider">
            <option value="All" selected="">All</option>
            <option value="Freemium">Freemium</option>
            <option value="Free">Free</option>
            <option value="Paid">Paid</option>
        </select>
    </form>
    <table id="example" class="table-auto w-full">
        <thead>
            <tr>
                <th class=" px-4 py-2">اسم الصنف</th>
                <th class="px-4 py-2">المتبقي</th>
                <th class="px-4 py-2">الناقص</th>
                <th class="px-4 py-2">مردودات المشتريات</th>
                <th class="px-4 py-2">مردودات المبيعات</th>
                <th class="px-4 py-2">مسموحات المبيعات</th>
                <th class="px-4 py-2">سعر الصنف</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2">شلك الامير</td>
                <td class="border px-4 py-2">30</td>
                <td class="border px-4 py-2">5</td>
                <td class="border px-4 py-2">2</td>
                <td class="border px-4 py-2">500</td>
                <td class="border px-4 py-2">500</td>
                <td class="border px-4 py-2">500</td>

            </tr>
            <tr>
                <td class="border px-4 py-2">شلك الامير</td>
                <td class="border px-4 py-2">30</td>
                <td class="border px-4 py-2">5</td>
                <td class="border px-4 py-2">2</td>
                <td class="border px-4 py-2">500</td>
                <td class="border px-4 py-2">500</td>
                <td class="border px-4 py-2">500</td>

            </tr>
            <tr>
                <td class="border px-4 py-2">رنج </td>
                <td class="border px-4 py-2">2</td>
                <td class="border px-4 py-2">10</td>
                <td class="border px-4 py-2">2</td>
                <td class="border px-4 py-2">1000</td>
                <td class="border px-4 py-2">1000</td>
                <td class="border px-4 py-2">1000</td>
            </tr>
            <tr>
                <td class="border px-4 py-2">رنج </td>
                <td class="border px-4 py-2">2</td>
                <td class="border px-4 py-2">10</td>
                <td class="border px-4 py-2">2</td>
                <td class="border px-4 py-2">1000</td>
                <td class="border px-4 py-2">1000</td>
                <td class="border px-4 py-2">1000</td>
            </tr>


            <!-- Add more rows as needed -->
        </tbody>
    </table>
</div>


{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<a class=" hover:shadow-form w-full rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none " href="{{route('donwload')}}">تنزيل التقرير</a>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            // Add any customization options here
        });
    });
</script> --}}


@endsection
