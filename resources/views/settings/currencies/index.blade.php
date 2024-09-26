@extends('layout')
@section('conm')
<div class="text-center flex justify-evenly  bg-gray-100 shadow-md p-4  w-1/2 mx-auto rounded-lg">


    <a href="{{route('settings.currencies.create')}}" class=" bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">اضافة عملة</a>
    <a href="{{route('settings.currencies.create')}}" class=" bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">تعديل العمله</a>
    <div>
        <label for="">العملة الافتراضية</label>
        <select  class="  bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">
            @foreach ($curr as $cur)
            <option value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
             @endforeach
        </select>
    </div>

</div>
<br>
<br>

    <div class=" flex container ">

      <div  class=" text-center  ">
      <label for="" class=" text-center" >العمله </label>
      <select   dir="ltr" id="accountty" class="inputSale "  required>
        @foreach ($curr as $cur)
            <option value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
        @endforeach
        </select>
     </div>

     <div  class=" text-center grow ">
      <label for="" class=" text-center"  > سعر الصرف  </label>
      <input id="maont" type="number" class="inputSale " required>
     </div>

      <div class="text-center grow">
      <label for="" class=" text-center " >المبلغ </label>
      <input id="maont" type="number" class="inputSale "  required>
      </div>

</div>



@endsection
