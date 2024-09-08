@extends('layout')
@section('conm')




<div class=" border-2 shadow-md rounded-lg my-2 ">
            <div class="bg-white p-8 rounded-lg  flex justify-between w-full ">
                <div class="text-right space-y-2  ">
                    <label class="text-lg text-black font-bold ">أسم المحل</label>
                    <h2 class="text-xl font-bold "><input class="  p-1  bg-transparent rounded-lg" type="text" placeholder="ادخل أسم المحل"></h2>
                    <label class="text-lg text-black font-bold ">المحل لأبيع...</label>
                    <p><input class="w-20 bg-transparent rounded-lg p-1" type="text" placeholder="......."><strong>-</strong><input class="w-20 bg-transparent rounded-lg p-1" type="text" placeholder="......"><strong>-</strong><input class="w-20 bg-transparent rounded-lg p-1" type="text" placeholder="......"></p>
                    <label class="text-lg text-black font-bold ">عنوان المحل</label>
                    <p><input class="w-60 bg-transparent rounded-lg p-1 placeholder:text-sm" type="text" placeholder=" العنوان:مثال..شارع 22 أمام بنك اليمن"></p>
                    <label class="text-lg text-black font-bold ">ارقام المحل</label>
                    <p> <input class="w-60 bg-transparent rounded-lg p-1" type="text" placeholder="التلفون:777777-777777-7777777"></p>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-24 h-20  flex items-center justify-center translate-x-10 relative">

                        <span class="absolute -top-10 text-xl  text-nowrap">أضافة شعار للمحل</span>
                        <div class="flex items-center justify-center">
                            <label class="flex items-center   text-white rounded cursor-pointer hover:bg-gray-200">
                                <svg width="114px" height="114px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.048"></g><g id="SVGRepo_iconCarrier"> <g id="Edit / Add_Plus_Square"> <path id="Vector" d="M8 12H12M12 12H16M12 12V16M12 12V8M4 16.8002V7.2002C4 6.08009 4 5.51962 4.21799 5.0918C4.40973 4.71547 4.71547 4.40973 5.0918 4.21799C5.51962 4 6.08009 4 7.2002 4H16.8002C17.9203 4 18.4801 4 18.9079 4.21799C19.2842 4.40973 19.5905 4.71547 19.7822 5.0918C20.0002 5.51962 20.0002 6.07967 20.0002 7.19978V16.7998C20.0002 17.9199 20.0002 18.48 19.7822 18.9078C19.5905 19.2841 19.2842 19.5905 18.9079 19.7822C18.4805 20 17.9215 20 16.8036 20H7.19691C6.07899 20 5.5192 20 5.0918 19.7822C4.71547 19.5905 4.40973 19.2842 4.21799 18.9079C4 18.4801 4 17.9203 4 16.8002Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g> </g></svg>
                                <input type="file" class="hidden" />
                            </label>
                        </div>
                    </div>
                </div>
                <div class="text-right space-y-2  ">
                    <label class="text-lg text-black font-bold "> أسم المحل بالإنجليزي</label>
                    <h2 class="text-xl font-bold "><input class="  p-1  bg-transparent rounded-lg" type="text" placeholder=" ادخل أسم المحل بالإنجليزي"></h2>
                    <label class="text-lg text-black font-bold "> المحل لأبيع ...</label>
                    <p><input class="w-20 bg-transparent rounded-lg p-1" type="text" placeholder="......."><strong>-</strong><input class="w-20 bg-transparent rounded-lg p-1" type="text" placeholder="......"><strong>-</strong><input class="w-20 bg-transparent rounded-lg p-1" type="text" placeholder="......"></p>
                    <label class="text-lg text-black font-bold ">عنوان المحل بالإنجليزي</label>
                    <p><input class="w-60 bg-transparent rounded-lg p-1 placeholder:text-sm" type="text" placeholder=" Address:Street 22 "></p>
                    <label class="text-lg text-black font-bold ">ارقام المحل بالإنجليزي</label>
                    <p> <input class="w-60 bg-transparent rounded-lg p-1" type="text" placeholder="Phones:77777-77777-77777"></p>
                </div>
            </div>
</div>
<button class="px-8 py-4  absolute right-5 bg-gradient-to-r from-indigo-700 to-indigo-500 text-white font-bold rounded-full transition-transform transform-gpu hover:-translate-y-1 hover:shadow-lg">
    حفظ
  </button>






@endsection
