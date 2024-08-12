@extends('layout')
@section('conm')
   
<div class="flex items-center justify-center p-5">
    <!-- Author: FormBold Team -->
    <div class="mx-auto w-full max-w-full bg-white">
        <form>
         <div class="-mx-3 flex flex-wrap">
                
                <div class=" px-1 sm:w-1/6">
                    <div class="mb-1">
                        <label for="name" class="mb-1 block text-base font-medium text-[#07074D]">
                          اسم الصنف
                        </label>
                        <input type="text" name="name" id="contact_person _name" placeholder="name"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>

                <div class=" px-1 sm:w-1/6">
                    <div class="mb-1">
                        <label for="barcod" class="mb-1 block text-base font-medium text-[#07074D]">
                      الباركود
                      </label>
                        <input type="number" name="barcod" id="contact_person _name" placeholder="barcod  "
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>

                <div class=" px-1 sm:w-1/6">
                    <div class="mb-1">
                        <label for="discount" class="mb-1 block text-base font-medium text-[#07074D]">
                            التخفيض  
                        </label>
                        <input type="number" name="discount" id="address" placeholder="discount"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>

                <div class="max-md:w-52 px-1 sm:w-1/6">
                    <div class="mb-1">
                        <label for="price" class="mb-1 block text-base font-medium text-[#07074D]">
                            سعر الصنف 
                        </label>
                        <input type="number" name="price" id="price" placeholder="price"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white    text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>

                <div class="max-md:w-52 px-1 sm:w-1/6">
                    <div class="mb-1">
                        <label for="quantity" class="mb-1 block text-base font-medium text-[#07074D]">
                             ك/ المتوفرة
                        </label>
                        <input type="number" name="quantity" id="address" placeholder="quantity"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>

                <div class="max-md:w-52 px-1 sm:w-1/6">
                    <div class="mb-1">
                        <label for="address" class="mb-1 block text-base font-medium text-[#07074D]">
                 الحالة
                        </label>
                        <input type="text" name="status" id="address" placeholder="status"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 sm:w-1/4">
                    <div class="mb-1">
                        <label for="address" class="mb-1 block text-base font-medium text-[#07074D]">
                              العلامة التجارية
                        </label>
                        <input type="number" name="address" id="address" placeholder="Contact Person Number Phone"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>
                <div class="max-md:w-52 px-1 sm:w-1/3">
                    <div class="mb-1">
               
                                 
                         <label for="address" class="mb-1 block text-base font-medium text-[#07074D]">
                            الوصف 
                        </label> 
                            <textarea  name="description" id="" cols="10" rows="1" placeholder="description"
                       class="w-full rounded-md border border-[#e0e0e0] bg-white  text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" 
               ></textarea>
                       
                    </div>
                </div>
                <div class="w-full px-1 sm:w-1/5">
                    <div class="mb-1">
                        <button
                        class="hover:shadow-form w-full rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none">
                       حفظ البيانات
                    </button</div>
                </div>
                
            </div>
            <div class="w-full px-1 sm:w-1/5">
                <div class="mb-1">
                    <button
                    class="hover:shadow-form w-full rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none">
                  الغاء
                </button</div>
            </div>
            
        </div>
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
                <tbody>
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
            </div>

           
        </form>
   
    </div>
</div>

<br>

@endsection