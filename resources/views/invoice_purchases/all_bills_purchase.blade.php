@extends('layout')
{{-- @extends('accounts.layout') --}}
@section('conm')
<div class="container max-h-screen">


<form  class="p-1 shadow-md  ">
    {{-- <label class="labelSale" for="email">إعدادات عرض البيانات </label> --}}
        <ul class="items-center w-full  font-medium text-gray-900 bg-white border border-indigo-700 rounded-lg sm:flex dark:bg-gray-700 dark:border-indigo-700 dark:text-white">
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-indigo-700">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale">إعدادات العرض والبحث  </label>
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale" checked >تلقائي </label>
                    <input checked id="horizontal-list-radio-license" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600 ">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-license" class="labelSale" >اليوم </label>
                    <input  id="horizontal-list-radio-license" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center ">
                    <label for="horizontal-list-radio-id" class="labelSale">هذا الاسبوع </label>
                    <input id="horizontal-list-radio-id" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center text-center ">
                    <label for="horizontal-list-radio-military" class="labelSale ">هذا الشهر</label>
                    <input id="horizontal-list-radio-military" type="radio" value="" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center  p-1">
                    <label for="horizontal" class="w-full  ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">من تاريخ </label>

                    <input name="horizontal" class="inputSale " id="" type="date" placeholder=""/>
                </div>
            </li>
            <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                <div class=" items-center  p-1">
                    <label for="horizontal-list-radio-passpot" class="w-full  ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"> الى تاريخ </label>
                    <input name="horizontal-list-radio-passpot" class="inputSale " id="" type="date" placeholder=""/>
                </div>
            </li>
        </ul>

    </form>
<div class="flex  gap-4 justify-center items-center  ">
    <div class="">

        <select class="relative  border border-gray-200 rounded-lg w-full max-w-lg" name="" id="">
            <option value="" selected>كل الفواتير</option>
            <option value="" >اول فاتوره</option>
            <option value="" >اخر فاتوره</option>
        </select>
        </div>

    <div class="border-gray-200 rounded-lg w-full max-w-lg">
        <input type="text" class="rounded-md w-full text-left" placeholder="Search" name="search" value="">

    </div>
</div>

<div class="flex   justify-center items-center overflow-y-scroll  ">
    <div class=" min-w-full  rounded-lg max-h-screen">

        <br>
        <div class="bg-white   shadow-lg  max-w-xl mx-auto  rounded-2xl p-1 " >
            @isset($Purchases)


            @foreach ($Purchases as $Purchase)


            <div class="bg-white   shadow-lg  max-w-xl mx-auto  rounded-2xl p-2 border-x-2 border-y-2 border-indigo-700" >
                <table   class="w-full ">
                    <thead class="tracking-tight   " >
                        <tr  class="bg-indigo-700 text-black  text-bold w-full border-x-2 border-y-2 border-indigo-700  ">
                            <td colspan="1" class=" text-right  p-1  bg-white text-black  ">
                                <div class="flex items-center ">
                                    <div class="text-indigo-700 text-sm ">اسم المورد : <label class="text-black" for="">{{$Purchase->purchase_id}}</label> </div>
                                </div>
                                <div class="text-indigo-700  text-sm"> التلفون: <label class="text-black" for=""> </label></div>
                                <div class="text-sm text-indigo-700">الرقم التعريفي :   <label class="text-black" for=""> </label></div>
                            </td>
                            <td colspan="2" class="p-1 ">
                                <h2 class="text-1xl font-bold text-white"> محلات الريعاني </h2>
                                <h2 class="text-1xl font-bold text-white"> icon  </h2>

                            </td>
                            <td colspan="1" class="   p-1 text-bold text-white ">
                                <div class=" text-white" > <label class="text-white text-sm" for="">01/05/2023</label>  :التاريخ</div>
                                <div class=""> <label class="text-white text-sm" for="">{{$Purchase->Purchase_invoice_id}}</label> :رقم الفاتورة  </div>
                                <div class=""> <label class="text-white text-sm" for="">{{$Purchase->purchase_id}}</label> :رقم الايصال  </div>

                            </td>

                        </tr>
                    </thead>
                <tbody>
                    <tr class="  border-b border-indigo-700">
                        <th scope="col"   class="  text-center text-sm"><div class="text-indigo-700 ">اجمالي الفاتورة</div></th>
                        <th scope="col"   class=" text-center text-sm"><div class="text-indigo-700 "> سعر الشراء</div></th>
                        <th  scope="col"  class=" text-center text-sm"><div class="text-indigo-700 ">سعر البيع </div></th>

                    </tr>
                    <tr class="">
                        <td class=" text-center  ">{{$Purchase->Purchase_price}}</td>
                        <td class=" text-center">{{$Purchase->Selling_price}}</td>
                        <td class=" text-center  ">{{$Purchase->Total}}</td>
                        <td class=" text-center text-indigo-700 "><a href="{{ route('print_bills_sale',$Purchase->purchase_id )}}" onclick="openSmallWindow(event)">
                          عرض المشتريات  الفاتورة   </a>
                       </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        @endforeach
        @endisset
        </div>
</div>
</div>



<br>
</div>
@endsection
<script>
    function openSmallWindow(event) {
        event.preventDefault(); // لمنع الفتح الافتراضي للرابط
        window.open("{{ route('print_bills_sale') }}", "_blank", "width=600,height=400,left=100,top=100");
    }
</script>
