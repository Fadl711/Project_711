@extends('production_system.index')
@section('productionSystem')
<div class="container">
      <div id="successMessage" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg" role="alert">
    <p class="font-bold">تم بنجاح!</p>
  </div>
<div id="errorMessage" style="display: none;" class="alert alert-danger"></div>
<div id="successMessage" style="display: none;" class="alert alert-success"></div>
     <div class=" px-1  bg-white rounded-xl ">
    <div class=" flex">
        <div class="w-full   py-1">
            <form id="invoicePurchases" method="POST" >
                @csrf
                <div class="flex gap-1">
                    <div class="flex gap-4">
                        @foreach ($PaymentType as $index => $item)
        <div class="flex">
            <label for="" class="labelSale">{{$item->label()}}</label>
            <input type="radio" name="Payment_type" value="{{$item->value}}" 
                {{ $index === 0 ? 'checked' : '' }} required>
        </div>
    @endforeach
                    </div>
                </div>
                <div class=" text-right grid grid-cols-2 md:grid-cols-4 gap-1">
                    <div>
                        <label for="transaction_type" class="labelSale transaction_type">نوع العملية</label>
                        <select  id="transaction_type" class="inputSale input-field select2" name="transaction_type">
                         <option selected value="{{ null }}">{{"حدد نوع العملية "}}</option>

                            @foreach ($transaction_types as $transactionType)
                                    <option value="{{ $transactionType->value }}">{{ $transactionType->label() }}--{{ $transactionType->value }}</option>
                            @endforeach
                        </select>
                    </div>
                        
                       <div class="supplier_div" >
                        <label for="Supplier_id" class="labelSale supplier_id">اسم المورد</label>
                        <select  name="Supplier_id" id="Supplier_id" class="input-field w-full select2 inputSale " >
                          <option selected value="{{ null }}">{{"حدد اسم المورد "}}</option>
                            @isset($subAccountSupplierid)
                            @foreach ($subAccountSupplierid as $Supplier)
                            <option value="{{$Supplier->sub_account_id}}">{{$Supplier->sub_name}}</option>
                             @endforeach
                             @endisset
                        </select>
                    </div>
                    <div >
                        <label for="main_account_debit_id" class="main_accountdebit  labelSale">  حساب التصدير  </label>
                       <select required name="main_account_debit_id" id="main_account_debit_id"  class="input-field  select2 inputSale" required >
                          @isset($main_accounts)
                        <option value="{{ null }}" selected>اختر الحساب</option>
                         @foreach ($main_accounts as $mainAccount)
                              <option value="{{$mainAccount->main_account_id}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                         @endforeach
                         @endisset 
                       </select>
                   </div>

                   <div >
                       <label for="sub_account_debit_id" class="labelSale  sub_account_debit_id ">  تحديد الدائن</label>
                       <select name="sub_account_debit_id" id="sub_account_debit_id"  class="input-field select2 inputSale" >
                           <option value="" selected>اختر الحساب الفرعي</option>
                           </select>
               </div>
               <div >
                <label for="Receipt_number" class="labelSale">رقم الإيصال</label>
                <input type="text" name="Receipt_number" id="Receipt_number" placeholder="0" class="inputSale english-numbers" />
               </div>
                    <div >
                        <label for="Total_invoice" class="labelSale">أجمالي الفاتورة</label>
                        <input type="text" name="Total_invoice" id="Total_invoice" placeholder="0" class="inputSale" />
                    </div>
                    @auth
                    <input type="hidden" name="User_id"  id="User_id" value="{{Auth::user()->id}}">
                    @endauth
                    <div>
                        <label for="Currency_id" class="labelSale">العملة الفاتورة</label>
                        <select    id="Currency_id" class="inputSale input-field " name="Currency_id"  >
                            @isset($Currency_name)
                          @foreach ($Currency_name as $cur)
                          <option @isset($cu) @selected($cur->currency_id==$cu->Currency_id)@endisset value="{{$cur->currency_id}}">{{$cur->currency_name}}</option>
                           @endforeach
                           @endisset
                          </select>
                        </div>
                    <div id="newInvoice1" style="display: block">
                        <button  id="newInvoice"
                        type="button"
                         class="inputSale flex font-bold">
                            اضافة الفاتورة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تسجيل حركة مخزون جديدة</h5>
        </div>
       
        <div class="card-body">
            <form  id="inventoryTransactionsForm" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transaction_type2">نوع الحركة</label>
                            <select name="transaction_type2" id="transaction_type2" class="form-control" required>
                                <option value="">اختر نوع الحركة</option>
                                @foreach(\App\Models\InventoryTransaction::TRANSACTION_TYPES as $key => $value)

                                <option value="{{ $key }}" {{ old('transaction_type2') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="item_id">المادة/المنتج</label>
                            <select name="item_id" id="item_id" 
                            class="form-control select2" required>
                                <option value="">اختر المادة/المنتج</option>
                                @foreach($items as $item)
                                <option value="{{ $item->product_id }}" {{ old('item_id') == $item->product_id ? 'selected' : '' }}>{{ $item->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quantity">الكمية</label>
                            <input type="number" step="0.001" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="unit_cost">التكلفة للوحدة</label>
                            <input type="number" step="0.00001" name="unit_cost" id="unit_cost" class="form-control" value="{{ old('unit_cost') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_cost">التكلفة الإجمالية</label>
                            <input type="number" step="0.01" name="total_cost" id="total_cost" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="warehouse_id">المخزن</label>
                            <select name="warehouse_id" id="warehouse_id" class="form-control select2" required>
                                <option value="">اختر المخزن</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->sub_account_id }}" {{ old('warehouse_id') == $warehouse->sub_account_id ? 'selected' : '' }}>{{ $warehouse->sub_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="location_id">الموقع (اختياري)</label>
                            <select name="location_id" id="location_id" class="form-control select2">
                                <option value="">اختر الموقع</option>
                                <!-- سيتم ملؤه بواسطة JavaScript -->
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="production_order_id">أمر الإنتاج (اختياري)</label>
                            <select name="production_order_id" id="production_order_id" class="form-control select2">
                                <option value="">اختر أمر الإنتاج</option>
                                @foreach($productionOrders as $order)
                                <option value="{{ $order->id }}" {{ old('production_order_id') == $order->id ? 'selected' : '' }}>{{ $order->order_number }}</option>
                                @endforeach
                            </select>
                        </div>
                          <div>
                <label for="purchase_invoice_id" class="labelSale">رقم الفاتورة</label>
                <input type="number" name="purchase_invoice_id" id="purchase_invoice_id" placeholder="0" class="inputSale"  />
            </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="transaction_date">تاريخ الحركة</label>
                            <input type="datetime-local" name="transaction_date" id="transaction_date" class="form-control" value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="notes">ملاحظات</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="button" id="TransactionsSave"  class="btn btn-primary">حفظ</button>
                    <a href="{{ route('inventory-transactions.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#main_account_debit_id').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي
    showAccounts(mainAccountId);
    setTimeout(() => {
        $('#main_account_debit_id').select2('close'); // إغلاق حقل الحساب الرئيسي بشكل صحيح
        $('#sub_account_debit_id').select2('open');
    }, 1000);

});

   $('#transaction_type').on('change', function() {
                 let type=$(this).val();
  
                 if(type==10){
                        main_accountdebit.html("   الحساب الرئيسي/ خسائر النقص  ").show();
                            sub_account_debit_id.html(" الحساب الفرعي/ خسارة النقص ").show();
                            supplier_id.html("اسم المورد (اختياري)").show();
                 }
                 if(type==11){
                        main_accountdebit.html("   الحساب الرئيسي/ خسائر الأتلاف  ").show();
                            sub_account_debit_id.html(" الحساب الفرعي/ خسارة الأتلاف ").show();
                            supplier_id.html("اسم المورد (اختياري)").show();
                        }
                 if(type==12){
                        main_accountdebit.html("   الحساب الرئيسي/  الجهة المنصرف منها   ").show();
                            sub_account_debit_id.html(" الحساب الفرعي/ الجهة المنصرف منها ").show();
                            supplier_id.html("اسم المورد (اختياري)").show();
                        }
                 if(type==13){
                        main_accountdebit.html("   الحساب الرئيسي/  الجهة التوريد منها   ").show();
                            sub_account_debit_id.html(" الحساب الفرعي/  الجهة التوريد منها ").show();
                            supplier_id.html("اسم المورد (اختياري)").show();
                        }
                        if(type==9){
                            main_accountdebit.html("   الحساب الرئيسي/ إيرادات زيادة المخزون  ").show();
                            sub_account_debit_id.html(" الحساب الفرعي/ إيراد زيادة المخزون  ").show();
                           supplier_id.html("اسم المورد (اختياري)").show();
                 }

                
    // $('#product_id').select2('open');
    $('#transaction_type').select2('close');
});

function showAccounts(mainAccountId)
{
    if(mainAccountId)
    {
     var  sub_account_debit_id= $('#sub_account_debit_id');
    }
   
    if (mainAccountId!==null) {

        $.ajax({
            url: "{{ url('/main-accounts/') }}/" + mainAccountId + "/sub-accounts", // استخدام القيم الديناميكية

            type: 'GET',
            dataType: 'json',

    success: function(data) {
        sub_account_debit_id.empty();
  const  subAccountOptions = data.map(subAccount =>
        `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
    ).join('');

// إضافة الخيارات الجديدة إلى القائمة الفرعية
sub_account_debit_id.append(subAccountOptions);
sub_account_debit_id.select2('destroy').select2();

// إعادة تهيئة Select2 بعد إضافة الخيارات
},
error: function(xhr) {
    console.error('حدث خطأ في الحصول على الحسابات الفرعية.', xhr.responseText);
}
});
};
}
    // const form = $('#invoicePurchases');

    const form = $('#invoicePurchases');
         const submitButton = $('#newInvoice');
       const   successMessage = $('#successMessage');
        const  errorMessage = $('#errorMessage'),
          transaction_type = $('.transaction_type');
      const    invoiceField = $('#purchase_invoice_id');
      const    supplierField = $('#supplier_name');
       const   main_accountdebit = $('.main_accountdebit');
        const  sub_account_debit_id = $('.sub_account_debit_id');
          
$('#newInvoice').click(function(event) {
                event.preventDefault();      
        submitButton.prop('disabled', true).text('جاري الإرسال...');
        // إخفاء الرسائل السابقة
        successMessage.hide();
        errorMessage.hide();
        // const formData = new FormData(form[0]);
                        var formData = $('#invoicePurchases').serialize();

      
        $.ajax({
            url: '{{ route("invoicePurchases.store") }}',
            method: 'POST',
            headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },            data: formData,
         
        })

        .done(function(response) {
            if (response.success) {
                // تحديث الحقول وإظهار رسالة النجاح
                if (invoiceField.length) {
                    invoiceField.val(response.invoice_number).trigger('change');
                }
                // if (supplierField.length) {
                //     supplierField.val(response.supplier_id).trigger('change');
                // }

                // $('#mainAccountsTable tbody').empty();
                successMessage.text(response.message).show();

                setTimeout(() => {
                    successMessage.hide();
                }, 2000); // إخفاء الرسالة بعد 2 ثانية
            }else {
                errorMessage.text(response.message || 'حدث خطأ غير معروف.').show();
            }
        })
        .fail(function(xhr) {
            if (xhr.status === 422) {
    const errors = xhr.responseJSON.errors;
    const firstErrorField = Object.keys(errors)[0];
    const firstErrorMessage = errors[firstErrorField][0];
    // إظهار الرسالة مع اسم الحقل
    errorMessage.html(`<strong>${firstErrorMessage}</strong>`).show();

    // تسليط الضوء على الحقل الخاطئ
    const errorField = $(`[name="${firstErrorField}"]`);
    errorField.focus();

    // فتح `select2` إذا كان الحقل من نوع `select2`
    if (errorField.hasClass('select2')) {
        errorField.select2('open');
    }
}
 else {
                errorMessage.text('حدث خطأ أثناء إرسال الطلب. حاول مرة أخرى لاحقاً.').show();
            }
        })
        .always(function() {
            submitButton.prop('disabled', false).text('إضافة الفاتورة');
        });
    });

   
         $('#TransactionsSave').click(function(event) {
                event.preventDefault();
                // const entrie_id = $('#entrie_id').val();

                // إظهار مؤشر التحميل
                Swal.fire({
                    title: 'جارٍ المعالجة',
                    html: 'يرجى الانتظار أثناء حفظ البيانات...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                var formData = $('#inventoryTransactionsForm').serialize();

                $.ajax({
                    url: '{{ route('inventory-transactions.store') }}',
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        Swal.close();

                        if (data.success) {
                            Swal.fire({
                                title: 'نجاح!',
                                text: data.success,
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            });

                            // if (entrie_id) {
                            //     var invoiceField = data.entrie_id;
                            //     const url =
                            //         `{{ route('restrictions.print', ':invoiceField') }}`
                            //         .replace(':invoiceField', invoiceField);
                            //     window.open(url, '_blank', 'width=600,height=800');

                            //     setTimeout(() => {
                            //         window.location.href =
                            //             '{{ route('restrictions.create') }}';
                            //     }, 1000);
                            // }

                            // $('#Amount_debit').val("");
                            // $('#sub_account_debit_id').select2('open');
                        } else {
                            Swal.fire({
                                title: 'خطأ!',
                                text: data.errorMessage || 'حدث خطأ غير متوقع',
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessages = '';

                            for (const field in errors) {
                                errorMessages += `${errors[field][0]}<br>`;
                                const inputField = $(`#${field}`);
                                const parentDiv = inputField.closest('div');
                                parentDiv.find('.error-message').remove();
                                inputField.addClass('border-red-500');
                                parentDiv.append(
                                    `<div class="error-message text-red-500 text-sm mt-1">${errors[field][0]}</div>`
                                );
                            }

                            Swal.fire({
                                title: 'خطأ في التحقق',
                                html: errorMessages,
                                icon: 'error',
                                timer: 5000,
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                title: 'خطأ!',
                                text: 'حدث خطأ أثناء الحفظ. يرجى المحاولة لاحقًا.',
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            });
    // حساب التكلفة الإجمالية تلقائياً
    $('#quantity, #unit_cost').on('input', function() {
        const quantity = parseFloat($('#quantity').val()) || 0;
        const unitCost = parseFloat($('#unit_cost').val()) || 0;
        $('#total_cost').val((quantity * unitCost).toFixed(2));
    });

    // جلب مواقع المخزن عند اختيار مخزن
    // $('#warehouse_id').on('change', function() {
    //     const warehouseId = $(this).val();
    //     $('#location_id').empty().append('<option value="">جاري التحميل...</option>');
        
    //     if (warehouseId) {
    //         $.get('/api/warehouses/' + warehouseId + '/locations', function(data) {
    //             $('#location_id').empty().append('<option value="">اختر الموقع</option>');
    //             $.each(data, function(key, location) {
    //                 $('#location_id').append('<option value="' + location.id + '">' + location.name + '</option>');
    //             });
    //         });
    //     } else {
    //         $('#location_id').empty().append('<option value="">اختر الموقع</option>');
    //     }
    // });

    // تهيئة Select2
    $('.select2').select2({
        placeholder: 'اختر عنصر',
        allowClear: true
    });
});
</script>
@endsection



