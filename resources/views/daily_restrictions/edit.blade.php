@extends('daily_restrictions.index')

@section('restrictions')
<form action="{{route('daily_restrictions.stor')}}" method="POST"  enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="page_id" class="block font-medium mb-2">رقم الصفحة</label>
@auth
@isset($dailyPage->page_id)
<input type="text" name="page_id" id="page_id" class=" rounded-md w-[10%]"  value="{{$dailyPage['page_id']}}">
@endisset


@endauth

    </div>
    <button type="submit">إنشاء صفحة جديدة</button>
</form>


<div id="successMessage" style="display: none;"></div>
<div id="errorMessage" style="display: none;"></div>

<form action="{{ route('daily_restrictions.update', $eail->entrie_id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="container mx-auto  px-4">
        <!-- Title -->
        <h2 class="text-1xl font-bold text-center ">إضافة قيد يومي</h2>

        <!-- Form Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- حساب المدين -->
            <div class="shadow-lg rounded-lg p-4 bg-white border">
                <h3 class="text-lg font-semibold mb-4">المدين</h3>
                <div class="mb-4">
                    <label for="account_debit_id" class="block font-medium mb-2">حساب المدين/الرئيسي</label>

                    <select name="account_debit_id" id="account_debit_id" dir="ltr" class="input-field  select2 inputSale" required>
                       <!-- إضافة خيارات الحسابات -->
                       @auth


                      <option value="" selected>اختر الحساب</option>
                      @foreach ($mainAccounts as $mainAccount)
                      @foreach ($SubAccounts as $item)
                      @if ($eail->account_debit_id==$item->sub_account_id)
                      @if ($item->Main_id==$mainAccount->main_account_id)
                      <option
                      @selected($mainAccount->where('main_account_id',$item->Main_id)->first()->account_name) value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                      @endif

                      @endif
                      @endforeach

                      @endforeach
                      @endauth
                    </select>
                </div>
                <div class="mb-4">

                    <label for="sub_account_debit_id" class="block font-medium mb-2 ">حساب المدين/الفرعي</label>
                    @auth

                    <select name="sub_account_debit_id" name="sub_account_debit_id" dir="ltr" class="input-field select2 inputSale" id="sub_account_debit_id">
                        <!-- سيتم تعبئة الخيارات بناءً على الحساب الرئيسي المحدد -->
                        <option value="" selected>اختر الحساب الفرعي</option>

                        @foreach ($SubAccounts as $item)
                        <option @selected($eail->account_debit_id==$item['sub_account_id']) value="{{$item['sub_account_id']}}">{{$item->sub_name}}-{{$item->sub_account_id}}</option>

                        @endforeach
                        </select>
                    @endauth
                </div>

            </div>

            <!-- حساب الدائن -->
            <div class="shadow-lg rounded-lg p-4 bg-white border">
                <h3 class="text-lg font-semibold mb-4">الدائن</h3>
                <div class="mb-4">
                    <label for="account_Credit_id" class="block font-medium mb-2">حساب الدائن/الرئيسي</label>
                    <select name="account_Credit_id" id="account_Credit_id" class=" select2 inputSale" required>
                        <option value="" selected>اختر الحساب</option>



                        <option value="" selected>اختر الحساب</option>
                        @foreach ($mainAccounts as $mainAccount)
                        @foreach ($SubAccounts as $item)
                        @if ($eail->account_Credit_id==$item->sub_account_id)
                        @if ($item->Main_id==$mainAccount->main_account_id)
                        <option
                        @selected($mainAccount->where('main_account_id',$item->Main_id)->first()->account_name) value="{{$mainAccount['main_account_id']}}">{{$mainAccount->account_name}}-{{$mainAccount->main_account_id}}</option>
                        @endif

                        @endif
                        @endforeach

                        @endforeach

                      </select>

                                            </select>
                </div>
                <div class="mb-4 ">
                    <label for="sub_account_Credit_id" class="block font-medium mb-2">حساب الدائن/الفرعي</label>
                    <select name="sub_account_Credit_id"  step="0.01" id="sub_account_Credit_id" class="block w-full select2 p-2 border rounded-md inputSale">
                        <option value="" selected>اختر الحساب الفرعي</option>
                        <!-- سيتم تعبئة الخيارات بناءً على الحساب الرئيسي المحدد -->

                        @foreach ($SubAccounts as $item)
                        <option @selected($eail->account_Credit_id==$item['sub_account_id']) value="{{$item['sub_account_id']}}">{{$item->sub_name}}-{{$item->sub_account_id}}</option>

                        @endforeach
                    </select>
                </div>

            </div>
        </div>
        <!-- تفاصيل إضافية -->
        <div class="shadow-lg rounded-lg p-4 bg-white border">
          <h3 class="text-lg font-semibold mb-4">تفاصيل إضافية</h3>
          <div class="grid grid-cols-2 md:grid-cols-2 gap-4">

          <div>
            <label for="Amount_debit" class="block font-medium mb-2">المبلغ المدين</label>
            <input name="Amount_debit" type="number" step="0.01" class=" inputSale " placeholder="أدخل المبلغ" value="{{$eail->Amount_debit}}" required>
        </div>


            <div class="">
                <label for="Currency_name" class="block font-medium mb-2">العملة</label>
                <select   dir="ltr" id="Currency_name" class="inputSale " name="Currency_name"   >
                    @auth


                  @foreach ($curr as $cur)
                  <option @isset($cu)
                  @selected($eail->Currency_name==$cur->currency_name)
                  @endisset
                  value="{{$cur->currency_name}}">{{$cur->currency_name}}</option>
                   @endforeach
                   @endauth
                  </select>
            </div>
        </div>
            <div class="">
                <label for="Statement" class="block font-medium mb-2">البيان</label>
                <textarea name="Statement" id="Statement" class="block w-full p-2 border rounded-md inputSale" placeholder="أدخل البيان" rows="4" required>{{$eail->Statement}}</textarea>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">

            <div class=" justify-">
              <button type="submit" id="submitButton" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  تعديل القيد
              </button>
          </div>
          <div class=" justify-">
            <button type="submit" id="submitButton" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                تعديل وطباعه
            </button>
        </div>
            </div>
            @auth

            <input type="hidden" name="User_id" value="{{ Auth::user()->id }}">
            @endauth

        </div>

    </div>
</form>
<script>
    $(document).ready(function() {
        // تفعيل Select2
        $('.select2').select2();

        // التركيز على الحقل الأول عند التحميل
        $('#account_debit_id').focus();



        // إضافة مؤشر تحميل

        // إرسال النموذج باستخدام AJAX بدون تحديث الصفحة


        // عند اختيار الحساب الرئيسي (المدين)
  // عند اختيار الحساب الرئيسي (المدين)
$('#account_debit_id').on('change', function() {
    const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (المدين)

    // تفريغ القائمة الفرعية إذا لم يتم اختيار حساب رئيسي
    $('#sub_account_debit_id').empty().append('<option value="">اختر الحساب الفرعي</option>');

    // التحقق من وجود قيمة
    if (mainAccountId) {
        // طلب AJAX لجلب الحسابات الفرعية بناءً على الحساب الرئيسي
        $.ajax({
            url: `/main-accounts/${mainAccountId}/sub-accounts`, // استخدام القيم الديناميكية
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // تعبئة الحسابات الفرعية الجديدة
                $.each(data, function(index, subAccount) {
                    $('#sub_account_debit_id').append(`<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`);
                });

                // إعادة تهيئة Select2 بعد إضافة الخيارات
                $('#sub_account_debit_id').select2('destroy').select2();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching sub-accounts:', error);
            }
        });
    }
});

        // عند اختيار الحساب الرئيسي (الدائن)
        $('#account_Credit_id').on('change', function() {
            const mainAccountId = $(this).val(); // الحصول على ID الحساب الرئيسي (الدائن)

            // تفريغ القائمة الفرعية إذا لم يتم اختيار حساب رئيسي
            $('#sub_account_Credit_id').empty().append('<option value="">اختر الحساب الفرعي</option>');

            // التحقق من وجود قيمة
            if (mainAccountId) {
                // طلب AJAX لجلب الحسابات الفرعية بناءً على الحساب الرئيسي
                $.ajax({
                    url: `/main-accounts/${mainAccountId}/sub-accounts`, // استخدام القيم الديناميكية
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // تعبئة الحسابات الفرعية الجديدة
                        const subAccountOptions = data.map(subAccount =>
                            `<option value="${subAccount.sub_account_id}">${subAccount.sub_name}</option>`
                        ).join('');

                        // إضافة الخيارات الجديدة إلى القائمة الفرعية
                        $('#sub_account_Credit_id').append(subAccountOptions);

                        // إعادة تهيئة Select2 بعد إضافة الخيارات
                        $('#sub_account_Credit_id').select2('destroy').select2();
                    },
                    error: function() {
                        console.error('Error fetching sub-accounts.');
                    }
                });
            }
        });
    });
    </script>

@endsection
