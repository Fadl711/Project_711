
<div class="flex justify-center space-x-2  text-sm  items-center h-14 p-2  shadow-md">


        <form class="mx-2 ">
            <select id="country" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected>اختار الصنف</option>
            <option value="US"> اسمنت</option>
            <option value="CA">رنج ابو مسكه</option>
            <option value="FR">رنج مائي</option>
            <option value="DE">معجون</option>
            </select>
        </form>
        <div class="">
            <span>من</span>
            <input class="rounded-lg " type="date" name="from" id="from"/>
            <span>الى</span>
            <input class="rounded-lg " type="date" name="to" id="to"/>
            <input type="button" class="w-32 rounded-md bg-[#6A64F1] py-2  px-8 text-center text-base font-semibold text-white outline-none" value="Filter" onclick="getData()" />
        </div>

</div>
