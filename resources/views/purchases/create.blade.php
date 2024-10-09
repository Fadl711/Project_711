@extends('layout')
@section('conm')
<style>
    /* تثبيت الأرقام بالإنجليزية */
    .english-numbers {
        font-feature-settings: 'tnum';
        direction: ltr;
        unicode-bidi: plaintext;
    }
    td{ 
      text-align: right;
    }
  </style>

<div id="successMessage" class="alert-success" style="display: none;"></div>
<div class="min-w-[20%] px-1 border-x border-y border-orange-950 rounded-xl p-2">
    <div class="text-bro flex items-center">
        <div class="w-full min-w-full bg-white">
            <form id="invoicePurchases">
                @csrf
                
                <div class="flex gap-4">
                    <div class="flex">
                        <label for="" class="labelSale">نقدا</label>
                        <input type="radio" name="Payment_type" value="نقدا" required>
                    </div>
                    <div class="flex">
                        <label for="" class="labelSale">اجل</label>
                        <input type="radio" name="Payment_type" value="اجل" required>
                    </div>
                    <div class="flex">
                        <label for="" class="labelSale">شيك</label>
                        <input type="radio" name="Payment_type" value="شيك" required>
                    </div>
                </div>
                <div class="md:justify-around text-right grid md:grid-cols-7">
                    <div class="">
                        <label for="Supplier_id" class="labelSale">اسم المورد</label>
                        <select name="Supplier_id" id="Supplier_id" dir="ltr" class="input-field w-full select2 inputSale" required>
                            @isset($AllSubAccounts)
                                @foreach ($AllSubAccounts as $subAccount)
                                    @if ($subAccount->Main_id == $mainAccount_supplier->main_account_id)
                                        <option value="{{$subAccount->Main_id}}">{{$subAccount->sub_name}}</option>
                                    @endif
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="mb-1">
                        <label for="Receipt_number" class="labelSale">رقم الإيصال</label>
                        <input type="number" name="Receipt_number" id="Receipt_number" placeholder="0" class="inputSale" />
                    </div>
                    <div class="mb-1">
                        <label for="Total_cost" class="labelSale">اجمالي التكلفة</label>
                        <input type="number" name="Total_cost" id="Total_cost" placeholder="0" class="inputSale" />
                    </div>
                    <div class="px-1">
                        <label for="Total_invoice" class="labelSale">أجمالي الفاتورة</label>
                        <input type="number" name="Total_invoice" id="Total_invoice" placeholder="0" class="inputSale" />
                    </div>
                    @auth
                    <input type="hidden" name="User_id" required id="User_id" value="{{Auth::user()->id}}">
                    @endauth
                    <div style="display: block">
                        <button id="newInvoice" class="inputSale flex font-bold">
                            اضافة الفاتورة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="flex max-md:block p-1">
    <div class="min-w-[30%] border-x border-y border-orange-950 rounded-xl">
        <form   id="ajaxForm">
            @csrf
            <div class="mb-1 p-1 w-full">
                <label for="product_id" class="btn">بحث عن المنتج</label>
                <select name="product_id" id="product_id" dir="ltr" class="input-field select2 inputSale" required>
                    <option value="" selected>اختر منتج</option>
                    @isset($products)
                        @foreach ($products as $product)
                            <option value="{{$product->product_id}}">{{$product->product_name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <div class="flex">
                <div class="mb-1 p-1">
                    <label for="product_name" class="btn">اسم المنتج</label>
                    <input type="text" name="product_name" id="product_name" class="inputSale " required />
                </div>
                <div class="mb-1 p-1 ">
                    <label for="Store_id" class="btn"> موقع المخزن</label>
                    <select name="Store_id"  dir="ltr" id="Store_id"  class="input-field  inputSale" required>
                        @isset($Warehouse)
                        @forelse ($Warehouse as $Warehous)
                          <option value="{{$Warehous->warehouse_id}}">{{$Warehous->Store_name}}</option>
                          @empty
                              <div>لايوجد بيانات حالية</div>
                          @endforelse
                        @endisset
                    </select>
                </div>
                <div class="mb-1 p-1">
                    <label for="Quantity" class="btn">الكمية</label>
                    <input type="number" name="Quantity" id="Quantity" placeholder="0" class="inputSale english-numbers" required />
                </div>
            </div>
            <div  class="col-span-6 sm:col-span-3 py-2">
                <button class="inputSale flex font-bold"   id="saveButton">اضافة الصنف</button>
            </div>
            <div class="flex">
                <div class="px-1">
                    <label for="Purchase_price" class="btn">سعر الشراء</label>
                    <input type="number" name="Purchase_price" id="Purchase_price" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                    <label for="Cost" class="btn">تكلفة الصنف</label>
                    <input type="number" name="Cost" id="Cost" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                    <label for="Total" class="btn">الاجمالي</label>
                    <input type="number" name="Total" id="Total" placeholder="0" class="inputSale" />
                </div>
            </div>
            <div class="flex">
                <div class="px-1">
                    <label for="Discount_earned" class="btn">الخصم المكتسب</label>
                    <input type="number" name="Discount_earned" id="Discount_earned" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                    <label for="Selling_price" class="btn">سعر البيع</label>
                    <input type="number" name="Selling_price" id="Selling_price" placeholder="0" class="inputSale" />
                </div>
                <div class="px-1">
                    <label for="Profit" class="btn">الربح</label>
                    <input type="number" name="Profit" id="Profit" placeholder="0" class="inputSale" />
                </div>
            </div>
            <div class="flex  ">
                <div class="px-1">
                    <label for="Barcode" class="btn">الباركود</label>
                    <input type="number" name="Barcode" id="Barcode" placeholder="0" class="inputSale" />
                </div>
                <div>
                <label for="Currency_id" class="btn">العملة الشراء</label>
                <select   dir="ltr" id="Currency_id" class="inputSale input-field " name="Currency_id"  >
                    @auth
                  @foreach ($Currency_name as $cur)
                  <option @isset($cu)
                  @selected($cur->currency_id==$cu->Currency_id)
                  @endisset
                  value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
                   @endforeach
                   @endauth
                  </select>
                </div>
                <div>
                    <label for="Exchange_rate" class="btn"> سعر الصرف</label>
                    <input type="number" name="Exchange_rate" id="Exchange_rate" class="inputSale" />
                </div>
            </div>
            
                <div class="px-1">
                    <label for="note" class="btn">الوصف</label>
                    <textarea name="note" id="note" placeholder="0" class="inputSale"></textarea>
            </div>
            <div class="flex">
                <div>
                <label for="purchase_invoice_id" class="labelSale">رقم الفاتورة</label>
                <input type="number" name="purchase_invoice_id" id="purchase_invoice_id" placeholder="0" class="inputSale" required />
            </div>
            <div class="">
                <label for="supplier_name" class="labelSale">رقم المورد</label>
                <input type="number" name="supplier_name" id="supplier_name" placeholder="0" class="inputSale" required />
            </div>
            </div>
            
            <div class="flex" id="printEndSave" style="display:">
                <div class="col-span-6 sm:col-span-3">
                    <button class="inputSale mt-2 flex" type="">
                        <svg class="w-6 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M16.444 18H19a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h2.556M17 11V5a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v6h10ZM7 15h10v4a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-4Z" />
                        </svg>
                        <span class="textNav mr-1">حفظ وطباعة</span>
                    </button>
                    </div>
                    <div class="flex flex-col">
                        <input type="hidden" name="User_id" value="{{Auth::user()->id}}"/>
                      </div>
                <div class="col-span-6 sm:col-span-3" >
                <button class="flex inputSale mt-2 " type="submit" >
                        <svg class="w-6 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M11 16h2m6.707-9.293-2.414-2.414A1 1 0 0 0 16.586 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7.414a1 1 0 0 0-.293-.707ZM16 20v-6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v6h8ZM9 4h6v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4Z"/>
                        </svg>
                        <span class="textNav mr-1"> حفظ</span>
                </button>
                </div>
            </div>
        </form>
<script type="text/javascript">
    $(document).ready(function () {
      const form = $('#ajaxForm');
      const successMessage = $('#successMessage');
      const errorMessage = $('#errorMessage');
      const inputs = $('.input-field'); // تحديد جميع الحقول
      const Product_name = $('#Product_name');
      Product_name.focus();
    form.on('keydown', function (event) {
          if (event.key === 'Enter') {
              event.preventDefault(); // منع الحفظ عند الضغط على زر Enter
          }
      });
      $(document).on('keydown', function (event) {      // التنقل بين الحقول باستخدام السهم الأيمن أو الأيسر
          if (event.key === "ArrowRight" || event.key === "ArrowLeft") {
              let currentIndex = inputs.index(document.activeElement);
              if (currentIndex !== -1) {
                  if (event.key === "ArrowRight") {
                      if (currentIndex < inputs.length - 1) {
                          $(inputs[currentIndex + 1]).focus(); // نقل التركيز إلى الحقل التالي
                      }
                  } else if (event.key === "ArrowLeft") {
                      if (currentIndex > 0) {
                          $(inputs[currentIndex - 1]).focus(); // نقل التركيز إلى الحقل السابق
                      }
                  }
              }
          }
      });
    
      $(document).keydown(function(event) {
            if (event.ctrlKey && event.shiftKey) {
                event.preventDefault(); // منع السلوك الافتراضي (حفظ الصفحة)
                saveData(); // استدعاء دالة الحفظ
            }
        });
        $('#saveButton').click(function() {
            saveData(); // استدعاء دالة الحفظ
        });
      function saveData() {
        event.preventDefault(); // منع تحديث الصفحة
            const formData = new FormData($('#ajaxForm')[0]);
            $.ajax({
                url: '{{ route("Purchases.storc") }}', // استبدل هذا بالمسار الخاص بك
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // إرسال التوكن الخاص بـ Laravel
                },
                data: formData,
                processData: false, // ضروري مع FormData
                contentType: false, // ضروري مع FormData
              success: function (data) {
                  if (data.success) {
                      successMessage.show().text('تم الحفظ بنجاح!');
                      setTimeout(() => {
                          successMessage.hide();
                      }, 500);
                      addToTable(data.purchase);
                      $('#product_name').val('');
                      $('#Barcode').val('');
                      $('#Quantity').val('');
                      $('#Purchase_price').val(''); 
                      $('#Selling_price').val(''); 
                      $('#Total').val('');
                      $('#Cost').val('');
                      $('#Discount_earned').val('');
                      $('#Profit').val('');
                      $('#Exchange_rate').val('');
                      $('#product_id').val('');
                      $('#Total_cost').val('');
                      $('#Total_invoice').val(data.Purchasesum);
                      $('#product_id').focus();           
                  } else {
                      // إظهار رسالة عند وجود نفس الاسم
                      errorMessage.show().text('يوجد نفس هذا الاسم من قبل');
                      Product_name.focus();
                      setTimeout(() => {
                        errorMessage.hide();
                      }, 1000);
                  }
              },
              error: function () {
                  errorMessage.show().text('حدث خطأ أثناء الحفظ.');
              }
          });
      };
    
    //   // وظيفة لإضافة البيانات إلى الجدول
      function addToTable(account) {
          const tableBody = $('#mainAccountsTable tbody');
          const newRow = `
              <tr>
                  <td class="text-right tagTd">${account.Product_name}</td>
                  <td class="text-right tagTd">${account.Barcode}</td>
                  <td class="text-right tagTd">${account.Quantity || 0}</td>
                  <td class="text-right tagTd">${account.Purchase_price || 0}</td>
                  <td class="text-right tagTd">${account.Selling_price}</td>
                  <td class="text-right tagTd">${account.Total}</td>
                  <td class="text-right tagTd">${account.Cost}</td>
                  <td class="text-right tagTd">${account.Discount_earned}</td>
                  <td class="text-right tagTd">${account.note}</td>
              </tr>
          `;
          tableBody.append(newRow); // إضافة الصف الجديد إلى الجدول
      }
    });
    </script>
</div>
<div class="  overflow-x-auto   px-1">
    <div class=" min-w-full  rounded-lg  max-h-[500px] ">
        <table id="mainAccountsTable" class="min-w-full leading-normal ">
            <thead class="tracking-tight ">
            <tr class="bgcolor">
                <th scope="col" class="leading-2 tagHt">اسم الصنف</th>
                <th scope="col" class="leading-2 tagHt  ">الباركود</th>
                <th scope="col" class="leading-2 tagHt ">الكمية</th>
                <th scope="col" class="leading-2 tagHt ">السعر الشراء</th>
                <th scope="col" class="leading-2 tagHt ">السعر البيع</th>
                <th scope="col" class="leading-2 tagHt"> الإجمالي</th>
                <th scope="col" class="leading-2 tagHt ">التكلفة</th>
                <th scope="col" class="leading-2 tagHt">التخفيض</th>
                <th scope="col" class="leading-2 tagHt">العلامة التجارية</th>
                <th scope="col" class="leading-2 tagHt">تعديل الشراء</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
        </tbody>
    </table>
</div>
</div>
</div>
<script src="{{ url('purchases.js') }}"></script> 
<style>
    .alert-success {
        color: green;
        font-weight: bold;
    }
  </style>
<script>
$(document).ready(function() {
        Barcode        = $('#Barcode'),
        product_name   = $('#product_name'), 
        Selling_price  = $('#Selling_price'), 
        Purchase_price = $('#Purchase_price'), 
        Quantity       = $('#Quantity'),
        Total_cost     = $('#Total_cost').val(),
        Cost           = $('#Cost').val(),
    // عند تغيير المنتج المختار في القائمة
    $('#product_id').on('change', function() {
        var productId = $(this).val(); // الحصول على قيمة المنتج المختار
        if (productId) { // تحقق من وجود منتج محدد
            $.ajax({
                url: `/api/products/search?id=${productId}`, // استدعاء API بناءً على product_id
                method: 'GET',
                success: function(product) {
                    displayProductDetails(product); // استعراض تفاصيل المنتج إذا تمت الاستجابة بنجاح
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText); // عرض الخطأ إذا حدث خطأ في الاستدعاء
                }
            });
        } else {
            $('#productDetails').hide(); // إخفاء التفاصيل إذا لم يتم اختيار منتج
        }
    });
var total_cost = parseFloat($('#Total_cost').val()); 
    var purchase_price = parseFloat($('#Purchase_price').val()); 
    // التأكد من أن السعر الإجمالي وسعر الشراء أرقام صالحة لتجنب قسمة على صفر أو أخطاء
    if (!isNaN(total_cost) && !isNaN(purchase_price) && purchase_price > 0) {
        var cost = total_cost / purchase_price; // حساب التكلفة
        $('#Cost').val(cost); // إضافة السعر إلى الحقل مع تقريبه إلى خانتين عشريتين
    } else {
        $('#Cost').val(''); // في حال وجود خطأ في المدخلات، يتم تفريغ الحقل
    }
    // وظيفة لاستعراض تفاصيل المنتج
    function displayProductDetails(product) {
        $('#Quantity').focus();
        const invoiceInput = $('#purchase_invoice_id');
        if (invoiceInput.length) {
            Barcode.val(product.Barcode).trigger('change');
           product_name  .val(product.product_name).trigger('change');
           Selling_price .val(product.Selling_price).trigger('change');
           Purchase_price.val(product.Purchase_price).trigger('change');
           var total_cost = parseFloat($('#Total_cost').val()); // جلب القيمة من الحقل كرقم عشري
           if ( product.Selling_price > 0 && product.Purchase_price > 0) {
            var profit = product.Selling_price - product.Purchase_price; // حساب التمويز
            $('#Profit').val(profit).trigger('change'); // ��ضافة النتيجة مع تقريبها لخانتين عشريتين
           } 
           else {
    $('#Profit').val(''); 
} 
//product.Purchase_price و total_cost التحقق من أن  قيم صالحة 
if (!isNaN(total_cost) && total_cost > 0 && product.Purchase_price > 0) {
    var cost = total_cost / product.Purchase_price; // حساب التكلفة
    $('#Cost').val(cost).trigger('change'); // إضافة النتيجة مع تقريبها لخانتين عشريتين
} else {
    $('#Cost').val(''); // في حال وجود خطأ أو قيم غير صالحة، يتم تفريغ الحقل
}
if (!isNaN(total_cost) && total_cost > 0 && product.Purchase_price > 0) {
    var cost = total_cost / product.Purchase_price; // حساب التكلفة
    $('#Cost').val(cost).trigger('change'); // إضافة النتيجة مع تقريبها لخانتين عشريتين
} else {
    $('#Cost').val(''); // في حال وجود خطأ أو قيم غير صالحة، يتم تفريغ الحقل
}   
        }
      }
});
</script>
  <script>
  $(function() { 
    const form = $('#invoicePurchases'),
          submitButton = $('#newInvoice'),
          product_id   = $('#product_id'),
           successMessage = $('#successMessage'),
           errorMessage =   $('#errorMessage'),

          invoiceField = $('#purchase_invoice_id'), // حقل رقم الفاتورة
          supplier_id = $('#supplier_name'), // حقل رقم الفاتورة
           // حقل رقم الفاتورة
          csrfToken = $('input[name="_token"]').val();
    // عند الضغط على زر الحفظ
    submitButton.click(function(e) {
        e.preventDefault(); // منع تحديث الصفحة
        // تعطيل الزر لتجنب الضغط المكرر
        submitButton.prop('disabled', true).text('جاري الإرسال...');
        // جمع بيانات النموذج باستخدام serialize
        const formData = new FormData(form[0]);
        $.ajax({
    url: '{{ route("invoicePurchases.store") }}',
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrfToken },
    data: formData,
    processData: false,
    contentType: false,
})
.done(function(response) {
    if (response.success) {
        const invoiceInput = $('#purchase_invoice_id');
        const invoiceInput2 = $('#supplier_name');
        if (invoiceInput.length || invoiceInput2.length) {
            invoiceField.val(response.invoice_number).trigger('change');
            supplier_id.val(response.supplier_id).trigger('change');

        } else {
            console.warn('حقل "رقم الفاتورة" غير موجود.');
        }
        $('#product_id').focus();
        successMessage.text(response.message).show();
                      setTimeout(() => {
                      successMessage.hide();
                      }, 500);
    } else {
        alert('خطأ: ' + (response.message || 'حدث خطأ غير معروف.'));
    }
})
.fail(function(xhr) {
    console.error('خطأ:', xhr.responseText);
    alert('حدث خطأ أثناء إرسال الطلب. حاول مرة أخرى لاحقاً.');
})
.always(function() {
    submitButton.prop('disabled', false).text('إضافة الفاتورة');
});
    });
    form.find('input').keydown(function(e) {
        if (e.key === "Enter") { 
            e.preventDefault(); // منع حفظ النموذج عند الضغط على Enter
            $(this).next('input').focus(); // الانتقال إلى الحقل التالي
        }
    });
});
  </script>
@endsection
