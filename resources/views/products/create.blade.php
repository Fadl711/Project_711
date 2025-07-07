@extends('layout')
@section('conm')
    <x-nav-products />
    <style>
        .select2-container .select2-selection--single {
            height: 40px;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            /* Border color */
        }
    </style>
    <div id="successAlert" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg"
        role="alert">
        {{-- <p class="font-bold">تم بنجاح!</p>
    <p>تمت إضافة المنتج بنجاح.</p> --}}
    </div>
    <div id="successAlert1" class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg"
        role="alert">
    </div>
    <div class="bg-blue-50 rounded-md p-2">
        <form id="products" method="POST" class="border-b text-sm">
            @csrf

            <div class="grid grid-cols-2 gap-1 md:grid-cols-8 lg:grid-cols-8">
                <div class="flex flex-col">
                    <label for="Barcode" class="labelSale">الباركود</label>
                    <input type="number" name="Barcode" placeholder="0" class="inputSale"
                        @isset($prod->Barcode)
          value="{{ $prod->Barcode }}"
          @endisset />
                </div>

                <div class="flex flex-col">
                    <label for="product_name" class="labelSale">اسم الصنف</label>
                    <input type="text" name="product_name" id="product_name" placeholder="name" class="inputSale"
                        @isset($prod->product_name)
          value="{{ $prod->product_name }}"
          @endisset
                        required />
                </div>



                <div class="flex flex-col">
                    <label for="Regular_discount" class="labelSale">خصم عادي</label>
                    <input type="number" name="Regular_discount" id="Regular_discount" value="0" class="inputSale"
                        @isset($prod->Regular_discount)
          value="{{ $prod->Regular_discount }}"

          @endisset />
                </div>

                <div class="flex flex-col">
                    <label for="Special_discount" class="labelSale">خصم خاص </label>
                    <input type="number" name="Special_discount" id="Special_discount" value="0" class="inputSale"
                        @isset($prod->Special_discount)
          value="{{ $prod->Special_discount }}"

          @endisset />
                </div>
                <div class="flex flex-col">
                    <label for="Quantity" class="labelSale">الكمية</label>
                    <input type="number" name="Quantity" id="Quantity" placeholder="0" class="inputSale"
                        @isset($prod->Quantity)
          value="{{ $prod->Quantity }}"

          @endisset />
                </div>

                <div>
                    <label for="account_debitid" class="labelSale"> المخزن</label>
                    {{-- warehouse_to_id --}}
                    <select name="account_debitid" id="account_debitid" dir="ltr" class=" select2  " style="">
                        <option selected value=""></option>


                        @isset($Warehouse)
                            @foreach ($Warehouse as $cur)
                                <option
                                    @isset($prod)
              @selected($cur->sub_account_id == $prod->warehouse_id)
              @endisset
                                    value="{{ $cur->sub_account_id }}">{{ $cur->sub_name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div>
                    <label for="Supplier_id" class="labelSale"> المورد الصنف</label>
                    <select name="Supplier_id" id="Supplier_id" dir="ltr" class="input-field w-full select2 inputSale">
                        <option selected value=""></option>
                        @isset($subAccountSupplierid)
                            @foreach ($subAccountSupplierid as $Supplier)
                                <option
                                    @isset($prod)
              @selected($Supplier->sub_account_id == $prod->supplier_id)
              @endisset
                                    value="{{ $Supplier->sub_account_id }}">
                                    {{ $Supplier->sub_name }}-{{ $Supplier->sub_account_id }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="note" class="labelSale"> ملاحظه </label>
                    <input type="text" name="note" id="note" class="inputSale"
                        @isset($prod->note)
          value="{{ $prod->note }}"

          @endisset />
                </div>

                <div class="flex flex-col">
                    <label for="expiry_date" class="labelSale"> تاريخ الانتهاء </label>
                    <input type="date" name="expiry_date" id="expiry_date" class="inputSale"
                        @isset($prod->expiry_date)
      value="{{ $prod->expiry_date }}"

      @endisset />
                </div>

            </div>
            <div class="border-b flex justify-between text-sm">
                <div class="grid grid-cols-2 gap-1 md:grid-cols-5 lg:grid-cols-5">
                    <div class="px-1">
                        <label for="cate" class="labelSale">اسم الوحدة</label>
                        <input name="cate" type="text" id="cate" placeholder="" class="inputSale" />
                    </div>
                    <div class="flex flex-col">
                        <label for="Purchase_price" class="labelSale">سعر الوحدة الشراء</label>
                        <input type="number" name="Purchase_price" id="Purchase_price" placeholder="0" class="inputSale"
                            @isset($prod)
              value="{{ $prod->Purchase_price }}"

              @endisset
                            required />
                    </div>
                    <div class="flex flex-col">
                        <label for="Selling_price" class="labelSale"> سعر الوحدة البيع</label>
                        <input type="number" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale"
                            @isset($prod)
              value="{{ $prod->Selling_price }}"

              @endisset
                            required />
                    </div>
                    <div>
                        <label for="Quantityprice" class="labelSale"> العبوة</label>
                        <input type="number" name="Quantityprice" id="Quantityprice" placeholder="0"
                            class="inputSale  english-numbers" required />
                    </div>

                    <div class="px-1">
                        <label for="product_id" class="block font-medium "> اسم الصنف</label>
                        <select name="product_id" id="product_id" class=" select2 inputSale" required>
                            <option value="" selected>اختر الصنف</option>
                            @isset($products)
                                @foreach ($products as $product)
                                    <option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>
            </div>

            @auth
                <div class="flex flex-col">
                    <input type="hidden" name="User_id" value="{{ Auth::user()->id }}" />
                </div>
            @endauth
            <div class="grid grid-cols-2 gap-1 md:grid-cols-8 lg:grid-cols-8">
                <div class="py-2">
                    <button id="newProduc"
                        class="flex bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ $editProduct ?? 'اضافة صنف' }}
                    </button>
                </div>
                <div class="py-2 mr-1 flex justify-between ml-1">
                    <button type="button" id="Category" name="Category"
                        class="flex bg-green-500 hover:bg-green-700 text-white font-bold  py-2 px-4 rounded">
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g id="Edit / Add_Plus_Circle">
                                    <path id="Vector"
                                        d="M8 12H12M12 12H16M12 12V16M12 12V8M12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21Z"
                                        stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        اضافة الوحدة
                    </button>
                </div>
                <div>
                    <label for="producid" class="labelSale"> رقم المنتج</label>
                    <input type="number" name="producid" id="producid" class="inputSale  english-numbers"
                        @isset($prod->product_id)
         value="{{ $prod->product_id }}"
         @endisset />
                </div>
                <div>
                    <label for="purchase_id" class="labelSale"> رقم القيد الافتتاحي للكمية</label>
                    <input type="number" name="purchase_id" id="purchase_id" class="inputSale  english-numbers"
                        @isset($purchaseid)
         value="{{ $purchaseid }}"

         @endisset />
                </div>
                <div>
                    <label for="Categorieid" class="labelSale"> رقم الوحدة</label>
                    <input type="number" name="Categorieid" id="Categorieid" class="inputSale  english-numbers" />
                </div>
            </div>


        </form>
    </div>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
        $(document).ready(function() {
            const form = $('form');

            // منع الحفظ عند الضغط على زر Enter
            form.on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });

            $('#newProduc').click(function(e) {
                e.preventDefault();

                // تأكد من استخدام $ لانتقاء العنصر
                const product_idUpdate = $('#producid').val();
                let url; // متغير لتخزين عنوان URL
                let type; // متغير لتخزين نوع الطلب
                var formData = $('#products').serialize();


                // إذا لم يكن هناك معرف منتج، استخدم دالة الإنشاء



                $.ajax({
                    url: '{{ route('products.store') }}',
                    type: 'POST',
                    data: formData,

                    success: function(data) {
                        if (data.success) {
                            // إعادة تعيين الحقول
                            $('input').val('');
                            // $('select').val('');

                            // إظهار التنبيه
                            $('#successAlert').text(data.message).removeClass('hidden');

                            // إخفاء التنبيه بعد 8 ثوانٍ
                            setTimeout(function() {
                                $('#successAlert').addClass('hidden');
                            }, 8000);
                            window.location.href = '{{ route('products.create') }}';


                        } else {
                            $('#successAlert1').text(data.message).removeClass('hidden');

                            // إخفاء التنبيه بعد 8 ثوانٍ
                            setTimeout(function() {
                                $('#successAlert1').addClass('hidden');
                            }, 8000);
                        }
                        if (product_idUpdate) {
                            // إعادة توجيه المستخدم إذا كان التحديث ناجحًا
                            window.location.href = '{{ route('products.create') }}';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('حدث خطأ:', error);
                    }
                });
            });
        });
        $(document).ready(function() {
            $('#Category').click(function(e) {

                e.preventDefault();
                var formData = $('#products').serialize();

                $.ajax({
                    url: '{{ route('Category.store') }}',

                    type: 'POST',
                    data: formData,

                    success: function(data) {
                        if (data.success) {
                            // إعادة تعيين الحقول
                            $('input').val('');
                            // $('select').val('');

                            // إظهار التنبيه
                            $('#successAlert').text(data.message).removeClass('hidden');

                            // إخفاء التنبيه بعد 8 ثوانٍ
                            setTimeout(function() {
                                $('#successAlert').addClass('hidden');
                            }, 8000);


                        } else {
                            $('#successAlert1').text(data.message).removeClass('hidden');

                            // إخفاء التنبيه بعد 8 ثوانٍ
                            setTimeout(function() {
                                $('#successAlert1').addClass('hidden');
                            }, 8000);
                        }
                        if (product_idUpdate) {
                            // إعادة توجيه المستخدم إذا كان التحديث ناجحًا
                            window.location.href = '{{ route('products.create') }}';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('حدث خطأ:', error);
                    }
                });
            });
        });
    </script>
@endsection
