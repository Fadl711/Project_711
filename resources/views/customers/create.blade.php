@extends('layout')

@section('conm')
<x-nav-customer/>

    
    <div id="displayContainer" class="overflow-y-auto max-h-[80vh] bg-white px-4 py-1 rounded-lg shadow-md">
    <div class="mx-auto w-full max-w-[650px] bg-white">
        <form>
            <div class="mb-5">
                <label for="name" class="mb-3 block text-base font-medium text-[#07074D]">
                  الأسم الرباعي
                </label>
                <input type="text" name="name" id="name" placeholder="Full Name"
                    class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
            </div>
            <div class="mb-5">
                <label for="phone" class="mb-3 block text-base font-medium text-[#07074D]">
                   رقم التلفون
                </label>
                <input type="number" name="phone" id="phone" placeholder=" Phone Number"
                    class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
            </div>
            <div class="mb-5">
                <label for="email" class="mb-3 block text-base font-medium text-[#07074D]">
              البريد الإلكتروني
                </label>
                <input type="email" name="email" id="email" placeholder="Email"
                    class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
            </div>
            <div class="mb-5">

            <label for="address" class="mb-3 block text-base font-medium text-[#07074D]">
                العنوان
              </label>
              <input type="text" name="address" id="address" placeholder="Address"
                  class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
            </div>
            <div class="-mx-3 flex flex-wrap">
                <div class="w-full px-3 sm:w-1/2">
                    <div class="mb-5">
                        <label for="contact_person _name" class="mb-3 block text-base font-medium text-[#07074D]">
                          اسم/ معرف العميل
                        </label>
                        <input type="text" name="contact_person _name" id="contact_person _name" placeholder="Contact Person Name"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>
                <div class="w-full px-3 sm:w-1/2">
                    <div class="mb-5">
                        <label for="address" class="mb-3 block text-base font-medium text-[#07074D]">
                            رقم تلفون/ معرف العميل
                        </label>
                        <input type="number" name="address" id="address" placeholder="Contact Person Number Phone"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                    </div>
                </div>
            </div>

           
                </div>
            </div>

            <div>
                <button
                    class="hover:shadow-form w-full rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none">
                    حفظ البيانات
                </button>
            </div>
        </form>
   
    </div></div>
@endsection