@extends('layout')
@section('conm')


<div class="bg-gray-100 dark:bg-gray-900 dark:text-white text-gray-600 h-screen flex overflow-hidden text-sm">

        <div class="flex-grow bg-white dark:bg-gray-900 overflow-y-auto">
          <div class="sm:px-7 sm:pt-7  pt-4 flex flex-col sm:w-full border-b border-gray-200 bg-white dark:bg-gray-900 dark:text-white dark:border-gray-800 sticky top-0">
            <div class="flex items-center space-x-3 sm:mt-7 mt-4  sm:text-lg">
              <a href="{{route('report.summary')}}" class="sm:px-3 border-b-2 {{ Request::is('summary') ? 'dark:alert("gamal")  text-blue-700 border-blue-700 ' : 'text-gray-600' }}   border-transparent  dark:text-white dark:border-white pb-1.5 ">كشف حساب</a>
              <a href="{{route('report.inventoryReport')}}" class="sm:px-3 border-b-2 border-transparent {{ Request::is('inventoryReport') ? 'text-blue-700 border-blue-700' : 'text-gray-600' }} dark:text-gray-400 pb-1.5"> تقارير المخازن </a>
              <a href="{{route('report.earningsReports')}}" class="sm:px-3 border-b-2 {{ Request::is('earningsReports') ? 'text-blue-700 border-blue-700' : 'text-gray-600' }} border-transparent   dark:text-gray-400 pb-1.5">تقارير ارباح وخسائر الاصناف</a>
              <a href="{{route('report.salesReport')}}" class="sm:px-3 border-b-2 {{ Request::is('salesReport') ? 'text-blue-700 border-blue-700' : 'text-gray-600' }} border-transparent dark:text-gray-400 pb-1.5">تقارير المبيعات</a>
              <a href="#" class="sm:px-3 border-b-2 border-transparent text-gray-600 dark:text-gray-400 pb-1.5 ">تقارير</a>
              <a href="" onclick="myfun()"  class="sm:px-3 border-b-2 border-transparent text-gray-600 dark:text-gray-400 pb-1.5 flex">
               <svg class="w-6 h-5 text-gray-800 dark:text-white"
                   aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linejoin="round"
                       stroke-width="2" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z"/>
                    </svg>
                       <span class=" mr-1">طباعة التقرير</span>

                </a>
            </div>
          </div>

            <form  class="p-1 shadow-md relative ">
                    <ul class="items-center w-full  font-medium text-gray-900 bg-white border border-indigo-700 rounded-lg sm:flex dark:bg-gray-700 dark:border-indigo-700 dark:text-white">
                        <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-indigo-700">
                            <div class=" items-center ">
                                <label for="horizontal-list-radio-license" class="labelSale">إعدادات العرض والبحث  </label>
                            </div>
                        </li>
                        @yield('nav')

                        <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600 ">
                            <div class=" items-center ">
                                <label for="horizontal-list-radio-license" class="labelSale" >الكل </label>
                                <input  id="horizontal-list-radio-license" type="radio" value="all" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            </div>
                        </li>
                        <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600 ">
                            <div class=" items-center ">
                                <label for="horizontal-list-radio-license" class="labelSale" >اليوم </label>
                                <input  id="horizontal-list-radio-license" type="radio" value="today" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            </div>
                        </li>
                        <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                            <div class=" items-center ">
                                <label for="horizontal-list-radio-id" class="labelSale">هذا الاسبوع </label>
                                <input id="horizontal-list-radio-id" type="radio" value="week" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            </div>
                        </li>
                        <li class="w-full text-center border-b border-indigo-700 sm:border-b-0 sm:border-r dark:border-gray-600">
                            <div class=" items-center text-center ">
                                <label for="horizontal-list-radio-military" class="labelSale ">هذا الشهر</label>
                                <input id="horizontal-list-radio-military" type="radio" value="month" name="list-radio" class="  bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
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
                        <div class="border-gray-200 rounded-lg   xl:absolute -top-12 left-2 sm:w-1/3">
                            <input type="text" class="rounded-md w-full text-left placeholder:text-right " placeholder="اسم {{$name}} او رقم {{$name}}" name="search" value="">
                        </div>
                </form>
                <br>
                @yield('report')
            </div>
  </div>
</div>

<script>
    function myfun(){

        if (window.location.href.includes('/summary')){
            window.open("summaryPdf", "_blank", "download");

        }
        else if(window.location.href.includes('/inventoryReport')){
            window.open("inventoryReportPdf", "_blank", "download");
        }
        else if(window.location.href.includes('/earningsReports')){
            window.open("earningsReportsPdf", "_blank", "download");
        }
        else if(window.location.href.includes('/salesReport')){
            window.open("salesReportPdf", "_blank", "download");
        }

    }


</script>
@endsection

