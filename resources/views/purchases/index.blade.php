@extends('layout')
@section('conm')
<div class="border-b mb-5 flex justify-between text-sm">
    <div class="text-bro flex items-center pb-2 pr-2 border-b-2 border-[#6A64F1] uppercase">



{{--     <p href="#" class="font-semibold inline-block text-base">المورد</p>
    <hr class="my-6 rounded-lg   border border-black rounded-s-lg mr-   bg-bro">
 --}}
<div class="mx-10  w-full min-w-full bg-white">

<div class="mb-4 md:flex md:justify-around  text-right">
    <div class=" px-1 ">
        <div class="mb-1">
            <label for="name" class="labelSale">
                اسم المورد
            </label>
            <input type="text" name="name" id="contact_person _name" placeholder="name"
                class="inputSale" />
        </div>
    </div>
    <div class=" px-1 ">
        <div class="mb-1">
            <label for="name" class="labelSale">
                 تلفون المورد
            </label>
            <input type="text" name="name" id="contact_person _name" placeholder="name"
                class="inputSale" />
        </div>
    </div>

    <div class=" px-1 ">
        <div class="mb-1">
            <label for="name" class="labelSale">
                  رقم الإيصال
            </label>
            <input type="text" name="name" id="contact_person _name" placeholder="name"
                class="inputSale" />
        </div>
    </div>
    <div class=" px-1 ">
        <div class="mb-1">
            <label for="name" class="labelSale">
أجمالي الفاتورة                    </label>
            <input type="text" name="name" id="contact_person _name" placeholder="name"
                class="inputSale" />
        </div>
    </div>
    <div class=" px-1 ">
        <div class="mb-1">
            <label for="name" class="labelSale">
المدفوع                    </label>
            <input type="text" name="name" id="contact_person _name" placeholder="name"
                class="inputSale" />
        </div>
    </div>
    <div class=" px-1 ">
        <div class="mb-1">
            <label for="name" class="labelSale">
المتبقي                    </label>
            <input type="text" name="name" id="contact_person _name" placeholder="name"
                class="inputSale" />
        </div>
    </div>
    <div class=" px-1 ">
        <div class="mb-1">
            <label for="name" class="labelSale">
اجمالي التكلفة                   </label>
            <input type="text" name="name" id="contact_person _name" placeholder="name"
                class="inputSale" />
        </div>
    </div>
    <div class=" px-1 ">
        <div class="mb-1">
            <label for="name" class="labelSale">
نوع الدفع                    </label>
            <select type="text" name="name" id="contact_person _name" placeholder="name"
                class="inputSale" >
            <option value="">دولار</option>
            <option value="">دولار</option>
            <option value="">دولار</option>
        </select>
        </div>
    </div>

</div>
</div>

</div>

</div>



<div class="">

{{-- ________________________________jamal________________________________ --}}
<div class="border-b mb-5 flex justify-between text-sm">
    <div class="text-bro flex items-center pb-2 pr-2 border-b-2 border-[#6A64F1] uppercase">



{{--     <p href="#" class="font-semibold inline-block ">بيانات المنتاج</p>
    <hr class=" rounded-lg   border border-black rounded-s-lg    bg-bro"> --}}
    <div class="mx-10  w-full max-w-full bg-white">
        <form>

         <div class="mb-4 md:flex md:justify-around   ">



                <div class=" px-1 ">
                    <div class="mb-1">
                        <label for="name" class="btn">
                          اسم الصنف
                        </label>
                        <input type="text" name="name" id="contact_person _name" placeholder="name"
                            class="inputSale" />
                    </div>
                </div>

                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="quantity" class="btn">
                            الكمية
                        </label>
                        <input type="number" name="quantity" id="address" placeholder="quantity"
                            class="inputSale" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="quantity" class="btn">
                            تكلفة الصنف
                        </label>
                        <input type="number" name="quantity" id="address" placeholder="quantity"
                            class="inputSale" />
                    </div>
                </div>

                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="price" class="btn">
                            سعر الشراء
                        </label>
                        <input type="number" name="price" id="price" placeholder="price"
                            class="inputSale" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="price" class="btn">
                            سعر البيع
                        </label>
                        <input type="number" name="price" id="price" placeholder="price"
                            class="inputSale" />
                    </div>
                </div>


                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="quantity" class="btn ">
                         الاجماليه
                        </label>
                        <input type="number" name="quantity" id="address" placeholder="quantity"
                            class="inputSale" />
                    </div>
                </div>
                <div class=" px-1  ">
                    <div class="mb-1">
                        <label for="barcod" class="btn">
                      الباركود
                      </label>
                        <input type="number" name="barcod" id="contact_person _name" placeholder="barcod  "
                            class="inputSale" />
                    </div>
                </div>

                <div class=" px-1 ">
                    <div class="mb-1">
                        <label for="discount" class="btn">
                            التخفيض
                        </label>
                        <input type="number" name="discount" id="address" placeholder="discount"
                            class="inputSale" />
                    </div>
                </div>

                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">
                        <label for="address" class="btn">
                              العلامة التجارية
                        </label>
                        <input type="number" name="address" id="address" placeholder="Contact Person Number Phone"
                            class="inputSale" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 ">
                    <div class="mb-1">


                         <label for="address" class="btn">
                            الوصف
                        </label>
                            <textarea  name="description" id=""  placeholder="description"
                       class="inputSale"
               ></textarea>



        </div>

                </div>
            </div>

        </form>

    </div>
</div>
</div>

<div class="">
<div class=" bg-white rounded-lg shadow-lg px-8  max-w- mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <img class="h-8 w-8 mr-2" src="https://tailwindflex.com/public/images/logos/favicon-32x32.png"
                alt="Logo" />
            <div class="text-gray-700 font-semibold text-lg">Your Company Name</div>

        </div>
        <div class="text-gray-700">
            <div class="font-bold text-xl mb-2">INVOICE</div>
            <div class="text-sm">Date: 01/05/2023</div>
            <div class="text-sm">Invoice #: INV12345</div>
        </div>
    </div>
    <div class=" border-b-2 border-gray-300 pb-8 mb-8">
        <div class="text-gray-700 md-2">John Doe</div>
        <div class="text-gray-700 md-2">123 Main St.</div>
        <div class="text-gray-700 md-2">Anytown, USA 12345</div>
        <div class="text-gray-700">johndoe@example.com</div>
    </div>
    <table class="w-full text-left mb-8">
        <thead>
            <tr>
                <th class="text-gray-700 font-bold uppercase py-2">Description</th>
                <th class="text-gray-700 font-bold uppercase py-2">Quantity</th>
                <th class="text-gray-700 font-bold uppercase py-2">Price</th>
                <th class="text-gray-700 font-bold uppercase py-2">Total</th>
            </tr>
        </thead>

        <tbody >
            <tr>
                <td class="py-4 text-gray-700">Product 1</td>
                <td class="py-4 text-gray-700">1</td>
                <td class="py-4 text-gray-700">$100.00</td>
                <td class="py-4 text-gray-700">$100.00</td>
            </tr>
            <tr>
                <td class="py-4 text-gray-700">Product 2</td>
                <td class="py-4 text-gray-700">2</td>
                <td class="py-4 text-gray-700">$50.00</td>
                <td class="py-4 text-gray-700">$100.00</td>
            </tr>
            <tr>
                <td class="py-4 text-gray-700">Product 3</td>
                <td class="py-4 text-gray-700">3</td>
                <td class="py-4 text-gray-700">$75.00</td>
                <td class="py-4 text-gray-700">$225.00</td>
            </tr>
        </tbody>

    </table>
    <div class="flex justify-end mb-8">
        <div class="text-gray-700 mr-2">Subtotal:</div>
        <div class="text-gray-700">$425.00</div>
    </div>
    <div class="text-right mb-8">
        <div class="text-gray-700 mr-2">Tax:</div>
        <div class="text-gray-700">$25.50</div>

    </div>
    <div class="flex justify-end mb-8">
        <div class="text-gray-700 mr-2">Total:</div>
        <div class="text-gray-700 font-bold text-xl">$450.50</div>
    </div>
    <div class="border-t-2 border-gray-300 pt-8 mb-8">
        <div class="text-gray-700 mb-2">Payment is due within 30 days. Late payments are subject to fees.</div>
        <div class="text-gray-700 mb-2">Please make checks payable to Your Company Name and mail to:</div>
        <div class="text-gray-700">123 Main St., Anytown, USA 12345</div>
    </div>
</div>

</div>


<br>

@endsection
