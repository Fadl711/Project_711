@extends('layout')
@section('conm')

<div class="flex ">
{{--     <hr class="h-full bg-[#6A64F1] w-[2px]  absolute right-[370px]"> --}}
    <div class=" w- p-2 ">
        <label class="labelSale  " for="my">قسم المردودات</label>
        <input    name="nameC" list="datalis" id="my"  class="inputSale " placeholder="المردود">
        <datalist  class="inputSale "  id="datalis">
            <option value="مردود العملاء" >
                <option  value="مردود الموردين" >

                </datalist >


            </div>
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
    </div>
  <div id="customers" style="display: none" >
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
     </div>
            </div>
        </form>
    </div>
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
}

});
// ____________________customers______________________


</script>

@endsection
