@extends('layout')
@section('conm')
<div class="  shadow my-2 ">
<form action="{{route('company_data.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
            <div class="bg-white p-8 rounded-lg  flex justify-between w-full ">
                <div class="text-right space-y-2  ">
                    <label class="text-lg text-black font-bold ">أسم المحل</label>
                    <h2 class="text-xl font-bold "><input name="com_name" value="{{$buss ? $buss->Company_Name :""}}" class="  p-1  bg-transparent rounded-lg" type="text" placeholder="ادخل أسم المحل" required></h2>
                    <label class="text-lg text-black font-bold ">المحل لأبيع...</label>
                    <p><input value="{{$buss ? $buss->Services :""}}" name="com_for" class="w-60 bg-transparent rounded-lg p-1" type="text" placeholder="......."></p>
                    <label class="text-lg text-black font-bold ">عنوان المحل</label>
                    <p><input value="{{$buss ? $buss->Company_Address :""}}" name="com_address" class="w-60 bg-transparent rounded-lg p-1 placeholder:text-sm" type="text" placeholder=" العنوان:مثال..شارع 22 أمام بنك اليمن" required></p>
                    <label class="text-lg text-black font-bold ">ارقام المحل</label>
                    <p> <input value="{{$buss ? $buss->Phone_Number :""}}" name="com_phones" class="w-60 bg-transparent rounded-lg p-1" type="text" placeholder="التلفون:777777-777777-7777777" required></p>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-24 h-20  flex items-center justify-center translate-x-10 relative">

                        <span class="absolute -top-20 text-xl  text-nowrap">أضافة شعار للمحل</span>
                        <br>
                       <div class="w-32 h-32 mb-1 border rounded-lg overflow-hidden relative bg-gray-100">
    <img src="{{$buss ? url('images/'.$buss->Company_Logo.'') :""}}" id="image" class="object-cover w-full h-32" src="https://placehold.co/300x300/e2e8f0/e2e8f0" />

    <div class="absolute top-0 left-0 right-0 bottom-0 w-full  cursor-pointer flex items-center justify-center" onClick="document.getElementById('fileInput').click()">
        <button type="button"
            style="background-color: rgba(255, 255, 255, 0.65)"
            class="hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 text-sm border border-gray-300 rounded-lg shadow-sm"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-camera" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                <path d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                <circle cx="12" cy="13" r="3" />
            </svg>
        </button>
    </div>
</div>
<input name="com_photo" id="fileInput" value="{{$buss ? url('images/'.$buss->Company_Logo.'') :""}}"  accept="image/*" class="hidden" type="file" onChange="let file = this.files[0];
    var reader = new FileReader();

    reader.onload = function (e) {
        document.getElementById('image').src = e.target.result;
        document.getElementById('image2').src = e.target.result;
    };

    reader.readAsDataURL(file);
">
                    </div>
                </div>
                <div class="text-right space-y-2  ">
                    <label class="text-lg text-black font-bold "> أسم المحل بالإنجليزي</label>
                    <h2 class="text-xl font-bold "><input value="{{$buss ? $buss->Company_NameE :""}}" name="com_nameE" class="  p-1  bg-transparent rounded-lg" type="text" placeholder=" ادخل أسم المحل بالإنجليزي"></h2>
                    <label class="text-lg text-black font-bold "> المحل لأبيع ...</label>
                    <p><input value="{{$buss ? $buss->ServicesE :""}}" name="com_forE" class="w-60 bg-transparent rounded-lg p-1" type="text" placeholder="......." required></p>
                    <label class="text-lg text-black font-bold ">عنوان المحل بالإنجليزي</label>
                    <p><input value="{{$buss ? $buss->Company_AddressE :""}}" name="com_addressE" class="w-60 bg-transparent rounded-lg p-1 placeholder:text-sm" type="text" placeholder=" Address:Street 22 " required></p>
                    <label class="text-lg text-black font-bold ">ارقام المحل بالإنجليزي</label>
                    <p> <input value="{{$buss ? $buss->Phone_Number :""}}" name="com_phonesE" class="w-60 bg-transparent rounded-lg p-1" type="text" placeholder="Phones:77777-77777-77777" required></p>
                </div>
            </div>
            <div class="text-right space-y-2 px-8  ">
<button type="submit" class="px-8 py-2 border rounded shadow">
    {{$buss ? "تعديل" :"حفظ"}}
</button>
  <a href="{{route('settings.index')}}"  class="px-8 py-2 border rounded shadow">
  الغاء
  </a>
</div>
<br>
</form>

</div>



@endsection
