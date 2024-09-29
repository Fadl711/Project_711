@extends('layout')
@section('conm')
<x-nav-products/>
<div class="-translate-x-[40%] w-1/2">
    <div id="successAlert" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
        <p class="font-bold">تم بنجاح!</p>
        <p>تمت إضافة المنتج بنجاح.</p>
      </div>
      <div id="successAlert1" class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
        <p class="font-bold">هناك خطاء</p>
        <p id="re"></p>
    </div>

    <form action="{{route('Category.update',$prod->categorie_id)}}" method="POST">
        @csrf
        @method('PUT')
            <div class="border-b flex justify-between text-sm">
                <div class="w-full border-x border-y border-orange-950 rounded-xl">
                    <div class="px-1">
                    <label for="barcod" class="btn">اسم الوحدة</label>
                    <input name="cate" type="text" value="{{$prod->Categorie_name}}" class="inputSale" />
                    </div>
                <div id="newProduc" class="py-2 mr-1 flex justify-between ml-1">
                    <button class="flex bg-green-500 hover:bg-green-700 text-white font-bold  py-2 px-4 rounded">

                         تعديل
                    </button>
                </div>
                </div>
            </div>

    </form>


</div>

<script>


    $(document).ready(function() {
        $('#newProduc button').click(function(e) {
            e.preventDefault();
            var url = "{{ route('Category.update', $prod->categorie_id) }}";
            var formData = new FormData($('form')[0]);
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    // إعادة تعيين الحقول
                    $('input').val('');
                    $('select').val('');

                    // إظهار التنبيه
                    $('#successAlert').removeClass('hidden');

                    // إخفاء التنبيه بعد 3 ثوانٍ
                    setTimeout(function() {
                        $('#successAlert').addClass('hidden');
                    }, 3000);

                    console.log('تمت الإضافة بنجاح');
                },
                error: function(xhr, status, error) {
                    $('#successAlert1').removeClass('hidden');
                    var errorMessage = JSON.parse(xhr.responseText).error;
                    $('#re').text(errorMessage);
                    if(xhr.status === 422){

                        console.log(errorMessage)
                    }

                    // إخفاء التنبيه بعد 3 ثوانٍ
                    setTimeout(function() {
                        $('#successAlert1').addClass('hidden');
                    }, 3000);
                }
            });
        });
    });
</script>
@endsection
