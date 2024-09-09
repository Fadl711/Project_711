@extends('layout')
@section('conm')
<style>


</style>

<div class="bg-gray-100 dark:bg-gray-900 dark:text-white text-gray-600 h-screen flex overflow-hidden text-sm">

        <div class="flex-grow bg-white dark:bg-gray-900 overflow-y-auto">
          <div class="sm:px-7 sm:pt-7 px-4 pt-4 flex flex-col w-full border-b border-gray-200 bg-white dark:bg-gray-900 dark:text-white dark:border-gray-800 sticky top-0">
            <div class="flex items-center space-x-3 sm:mt-7 mt-4">
              <a href="#" class="px-3 border-b-2 border-blue-500 text-blue-500 dark:text-white dark:border-white pb-1.5">كشف حساب</a>
              <a href="#" class="px-3 border-b-2 border-transparent text-gray-600 dark:text-gray-400 pb-1.5">تقارير المبيعات</a>
              <a href="#" class="px-3 border-b-2 border-transparent text-gray-600 dark:text-gray-400 pb-1.5">تقارير المشتريات</a>
              <a href="#" class="px-3 border-b-2 border-transparent text-gray-600 dark:text-gray-400 pb-1.5 sm:block hidden">تقارير </a>
              <a href="#" class="px-3 border-b-2 border-transparent text-gray-600 dark:text-gray-400 pb-1.5 sm:block hidden">تقارير</a>
            </div>
          </div>
          <div class="sm:p-7 p-4">
            <div class="flex w-full items-center mb-7">
                <div class="flex justify-center space-x-2  text-sm  items-center h-10 p-2  shadow-md">
                    <div class="mx-2 ">
                        <select style="background-image: none" id="country" class=" appearance-auto  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>اختار </option>
                        <option value="US"></option>
                        <option value="CA"> </option>
                        <option value="FR"> </option>
                        <option value="DE"></option>
                        </select>
                    </div>
                    <div class="">
                        <span>من</span>
                        <input class="rounded-lg " type="date" name="from" id="from"/>
                        <span>الى</span>
                        <input class="rounded-lg " type="date" name="to" id="to"/>
                        <input type="button" class="w-32 rounded-md bg-[#6A64F1] py-2  px-8 text-center text-base font-semibold text-white outline-none" value="بحث " />
                    </div>
                </div>
            </div>
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
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
