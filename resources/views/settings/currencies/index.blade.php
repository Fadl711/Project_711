@extends('layout')
@section('conm')

<div class="text-center flex justify-evenly  bg-gray-100 shadow-md p-4  w-1/2 mx-auto rounded-lg">


    <a href="{{route('settings.currencies.create')}}" class=" bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">اضافة عملة</a>
    <a href="{{route('settings.currencies.create')}}" class=" bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">تعديل العمله</a>
    <div>
        <label for="">العملة الافتراضية</label>
        <select  id="default-currency" name="default_currency"  class="  bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">

            @foreach ($curr as $cur)
            <option @isset($cu)
            @selected($cur->currency_id==$cu->Currency_id)
            @endisset
            value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
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
        <option @isset($cu)
        @selected($cur->currency_id==$cu->Currency_id)
        @endisset
        value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
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

<script>
$('#default-currency').on('change', function() {
    var currencyId = $(this).val();
    var token = '{{ csrf_token() }}'; // Add this line
    $.ajax({
        type: 'POST',
        url: '{{ route('set-default-currency') }}',
        data: {
            _token: token, // Add this line
            currency_id: currencyId
        },
        success: function(data) {
            // Update the default currency in the UI if needed
        }
    });
});
</script>

@endsection
