@extends('layout')
@section('conm')
<div class="p-4">

    <label for="refund">المردودات</label>
<select name="" id="refund">
<option value="">مردود الموزع </option>
<option value="">مردود العميل </option>
</select>
</div>
<div class="border-b mb-5 flex justify-between text-sm">
    <div class="text-bro flex items-center pb-2 pr-2 border-b-2 border-[#6A64F1] uppercase">

<p href="#" class="font-semibold inline-block ">بيانات المنتج</p>
    <hr class=" rounded-lg   border border-black rounded-s-lg    bg-bro">
    <div class="mx-10  w-full max-w-full bg-white">
        <form>

         <div class="-mx-3 grid grid-cols-2 sm:grid-cols-5  ">



                <div class=" px-1 ">
                    <div class="mb-1">
                        <label for="name" class="mb-1 block text-base font-medium text-[#07074D]">
                          اسم الصنف
                        </label>
                        <input type="text" name="name" id="contact_person _name" placeholder="name"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>

                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="quantity" class="mb-1 block text-base font-medium text-[#07074D]">
                            الكمية
                        </label>
                        <input type="number" name="quantity" id="address" placeholder="quantity"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="quantity" class="mb-1 block text-base font-medium text-[#07074D]">
                            تكلفة الصنف
                        </label>
                        <input type="number" name="quantity" id="address" placeholder="quantity"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>







                <div class=" px-1  ">
                    <div class="mb-1">
                        <label for="barcod" class="mb-1 block text-base font-medium text-[#07074D]">
                      الباركود
                      </label>
                        <input type="number" name="barcod" id="contact_person _name" placeholder="barcod  "
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="date" class="mb-1 block text-base font-medium text-[#07074D]">
                              تاريخ الشراء/المردود
                        </label>
                        <input type="date" name="date_buy" id="date_buy" placeholder="Contact Person Number Phone"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>
        </div>


     </div>
            </div>

        </form>

    </div>
</div>
</div>

@endsection
