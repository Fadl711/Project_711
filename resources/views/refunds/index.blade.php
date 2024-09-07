@extends('layout')
@section('conm')

<form class=" relative p-2  w-52">
    <label class="labelSale  " for="my">قسم المردودات</label>
    <input    name="nameC" list="datalis" id="my"  class="inputSale " placeholder="المردود">
    <datalist  class="inputSale "  id="datalis">
        <option value="مردود العملاء" >
            <option  value="مردود الموردين" >

            </datalist >
{{--                 <button id="rr" class="absolute left-3 top-10 " type="reset"><svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7 9.5L12 14.5M12 9.5L7 14.5M19.4922 13.9546L16.5608 17.7546C16.2082 18.2115 16.032 18.44 15.8107 18.6047C15.6146 18.7505 15.3935 18.8592 15.1583 18.9253C14.8928 19 14.6042 19 14.0271 19H6.2C5.07989 19 4.51984 19 4.09202 18.782C3.71569 18.5903 3.40973 18.2843 3.21799 17.908C3 17.4802 3 16.9201 3 15.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.07989 5 6.2 5H14.0271C14.6042 5 14.8928 5 15.1583 5.07467C15.3935 5.14081 15.6146 5.2495 15.8107 5.39534C16.032 5.55998 16.2082 5.78846 16.5608 6.24543L19.4922 10.0454C20.0318 10.7449 20.3016 11.0947 20.4054 11.4804C20.4969 11.8207 20.4969 12.1793 20.4054 12.5196C20.3016 12.9053 20.0318 13.2551 19.4922 13.9546Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg></button>
--}}

        </form>
<div class="flex">
    {{--     <hr class="h-full bg-[#6A64F1] w-[2px]  absolute right-[370px]"> --}}
  <div id="supplirs" style="display: none">
    <h1 class="text-center underline mt-2  text-2xl">مردودات الموردين</h1>
    <div class="border-b mb-5 flex justify-between text-sm mt-5">
    <div class="text-bro flex items-center pb-2 pr-2 border-b-2 border-[#6A64F1] uppercase">

    <div class="mx-10  w-full max-w-full bg-white">
        <form>

         <div class="mb-4 md:flex md:justify-around ">
                <div class=" px-1 ">
                    <div class="mb-1">
                        <label for="name" class="labelSale">
                          اسم المورد
                        </label>
                        <input type="text" name="name" id="contact_person _name" placeholder="name"
                            class="inputSale" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="quantity" class="labelSale">
                            رقم الموزع
                        </label>
                        <input type="number" name="quantity" id="address" placeholder="quantity"
                            class="inputSale" />
                    </div>
                </div>
                <div class=" px-1 ">
                    <div class="mb-1">
                        <label for="name" class="labelSale">
                          اسم الصنف المردود
                        </label>
                        <input type="text" name="name" id="contact_person _name" placeholder="name" class="inputSale" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="quantity" class="labelSale"> الكمية المردوده </label>
                        <input type="number" name="quantity" id="address" placeholder="quantity"
                            class="inputSale" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="quantity" class="labelSale">
                            تكلفة الصنف
                        </label>
                        <input type="number" name="quantity" id="address" placeholder="quantity"
                            class="inputSale" />
                    </div>
                </div>
        </div>
     </div>
            </div>
        </form>
    </div>
    </div>
    </div>


  <div id="customers" class="w-[1000px]" style="display: none" >
    <h1 class="text-center underline mt-2  text-2xl">مردودات العملاء</h1>
    <div class="border-b mb-5 flex justify-between text-sm mt-5">
    <div class="text-bro flex items-center pb-2 pr-2 border-b-2 border-[#6A64F1] uppercase">

    <div class="mx-10  w-full max-w-full bg-white">
        <form>

            <div class="mb-4 md:flex md:justify-around ">
            <div class=" px-1 ">
                <div class="mb-1">
                    <label for="name" class="labelSale">
                      اسم العميل
                    </label>
                    <input type="text" name="name" id="contact_person _name" placeholder="name"
                        class="inputSale" />
                </div>
            </div>
            <div class="max-md:w-52 px-1 ">
                <div class="mb-1">
                    <label for="quantity" class="labelSale">
                        رقم العميل
                    </label>
                    <input type="number" name="quantity" id="address" placeholder="quantity"
                        class="inputSale" />
                </div>
            </div>
            <div class=" px-1 ">
                <div class="mb-1">
                    <label for="name" class="labelSale">
                      اسم الصنف المردود
                    </label>
                    <input type="text" name="name" id="contact_person _name" placeholder="name"
                        class="inputSale" />
                </div>
            </div>
            <div class="max-md:w-52 px-1 ">
                <div class="mb-1">
                    <label for="quantity" class="labelSale">
                    الكمية المردوده
                    </label>
                    <input type="number" name="quantity" id="address" placeholder="quantity"
                        class="inputSale" />
                </div>
            </div>
            <div class="max-md:w-52 px-1 ">
                <div class="mb-1">
                    <label for="quantity" class="labelSale">
                    سعر الصنف
                    </label>
                    <input type="number" name="quantity" id="address" placeholder="quantity"
                        class="inputSale" />
                </div>
            </div>
        </div>
        </form>
    </div>
    </div>
    </div>

</div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script  >
    document.getElementById('my').addEventListener('input',
    function(){

        var displayTag= document.getElementById('my').value;

        if(displayTag=="مردود الموردين"){
            var supplir1=document.getElementById('customers');
            supplir1.style.display="none";
            var supplir=document.getElementById('supplirs');
            supplir.style.display="block";
        }
        else if(displayTag=="مردود العملاء"){
            var supplir=document.getElementById('supplirs');
            supplir.style.display="none";
            var supplir1=document.getElementById('customers');
            supplir1.style.display="block";
}else{
    var supplir=document.getElementById('supplirs');
            supplir.style.display="none";
            var supplir1=document.getElementById('customers');
            supplir1.style.display="none";
}

});
// ____________________customers______________________


</script>

@endsection
