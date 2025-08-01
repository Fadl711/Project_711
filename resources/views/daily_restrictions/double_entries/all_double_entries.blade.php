@extends('layout')
@section('conm')
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        .header-section {
            background-color: #f3f4f6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        table th,
        table td {
            text-align: center;
        }

        .no-print button {
            transition: background-color 0.3s ease;
        }

        .no-print button:hover {
            transform: scale(1.05);
        }

        @media print {
            .no-print {
                display: none;
            }

        }

        body {

            font-family: Arial, sans-serif;
            /* الخط الافتراضي */
        }
    </style>
    <x-nav-transfer-restriction />

    @if (session('success'))
        <div id="success-message" class=" fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    <div id="successAlert" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg"
        role="alert">

    </div>
    <div id="successAlert1" class="hidden fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg"
        role="alert">
    </div>


    <div class=" overflow-x-auto bg-white shadow-md sm:rounded-lg    w-full px-4 py-2   max-h-[80vh]">
        <table class="text-sm   font-semibold w-full overflow-y-auto max-h-[80vh] border-collapse">
            <thead class="bg-[#2430d3] text-white sticky top-0  uppercase dark:bg-gray-700 dark:text-gray-400">
                <tr class="">

                    <th colspan="5" class=" text-right bg-white border-whi">
                        <div class="relative  border text-black border-whi rounded-lg   ">
                            <input id="search" name="search" type="text"
                                class="rounded-md w-full text-right placeholder:text-right"
                                placeholder="ابحث با اسم الحساب او رقم القيد  ">
                        </div>
                    </th>

                </tr>
                <tr>
                    <th class="rounded-s-lg">رقم القيد المزدوج</th>
                    <th class="">نوع القيد</th>
                    <th class=""> اسم الحساب</th>
                    <th class=""> البيان</th>
                    <th class=""> عدد القيود </th>
                    <th class=""> الإجمالي</th>
                    <th class="">تاريخ القيد</th>
                    <th class="">تاريخ التحديث</th>
                    <th class="">المستخدم</th>
                    <th class=" rounded-e-lg ">عرض </th>
                </tr>
            </thead>
            <tbody id="products-table">
                @forelse ($doubleEntries as $doubleEntrie)
                    <tr class="hover:bg-gray-50 transition-colors duration-200" id="row-{{ $doubleEntrie->id }}">
                        <td class="border-b py-3 px-2 text-center">{{ $doubleEntrie->id }}</td>
                        <td class="border-b py-3 px-2 text-center">{{ $doubleEntrie->account_type }}</td>
                        <td class="border-b py-3 px-2 text-center">
                            {{ $doubleEntrie->account_type == 'دائن' ? 'من/' . $doubleEntrie->creditAccount->sub_name : 'الى/' . $doubleEntrie->debitAccount->sub_name }}
                        </td>
                        <td class="border-b py-3 px-2 text-right bg-gray-100 font-medium ">
                            {{ $doubleEntrie->Statement }}
                        </td>
                        <td class="border-b py-3 px-2 text-center bg-gray-100 font-medium">
                            {{ $doubleEntrie->double_entries->count() >= 1 ? $doubleEntrie->double_entries->count() : 0 }}
                        </td>
                        <td class="border-b py-3 px-2 text-center bg-gray-100 font-semibold">
                            {{ number_format($doubleEntrie->double_entries->sum('amount_credit')) }}

                        </td>
                        <td class="border-b py-3 px-2 text-center">{{ $doubleEntrie->created_at->format('Y-m-d') }}</td>
                        <td class="border-b py-3 px-2 text-center">{{ $doubleEntrie->updated_at->format('Y-m-d') }}</td>
                        <td class="border-b py-3 px-2 text-center">{{ $doubleEntrie->user->name }}</td>
                        <td class="border-b py-3 px-2">
                            <div class="flex justify-center space-x-2 rtl:space-x-reverse">
                                <a href="{{ route('double_entry.show', $doubleEntrie->id) }}" target="_blank"
                                    class="text-[#2430d3] hover:bg-blue-50 p-2 rounded-full transition-colors duration-200"
                                    title="عرض">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <button type="button"
                                    class="text-red-600 hover:bg-red-50 p-2 rounded-full transition-colors duration-200 delete-payment"
                                    data-id="{{ $doubleEntrie->id }}" title="حذف">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center py-4 text-gray-500">
                            لا توجد قيود مزدوجة لعرضها في هذه الصفحة.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {

            const searchInput = document.getElementById("search");
            const tableRows = document.querySelectorAll("#products-table tr");

            searchInput.addEventListener("keyup", function() {
                const searchTerm = this.value.trim().toLowerCase();

                tableRows.forEach(function(row) {
                    const entrieId = row.children[0]?.textContent.trim().toLowerCase() || "";
                    const debitName = row.children[2]?.textContent.trim().toLowerCase() || "";

                    const match =
                        entrieId.includes(searchTerm) ||
                        debitName.includes(searchTerm);

                    row.style.display = match ? "" : "none";
                });
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.delete-payment', async function(e) {
                e.preventDefault();
                // const url = "{{ url('daily_restrictions') }}"; // استخدم مُتغير
                let paymentId = $(this).data('id');
                console.log('Payment ID:', paymentId); // تحقق من قيمة paymentId

                // const url = act.replace(':id', paymentId);
                const result = await Swal.fire({
                    title: 'هل أنت متأكد من حذف هذا القيد المزدوج',
                    text: "سوف يتم حذف جميع القيود المرتبطه به!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء'
                });
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('double_entry_delete/') }}/" + paymentId,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            console.log(response); // تحقق من استجابة الخادم
                            if (response.success) {
                                Swal.fire({
                                    title: 'تم الحذف!',
                                    text: response.success ??
                                        'تمت عملية الحذف بنجاح',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                var rowSelector = '#row' + paymentId;
                                $(rowSelector).hide();
                                $(rowSelector).css('display', 'none');
                                $(rowSelector).slideUp();
                                $('#row-' + paymentId).fadeOut(); // إخفاء الصف

                                $(rowSelector).css('display', 'none');
                                $(rowSelector).fadeOut(function() {
                                    console.log(
                                        'Row faded out successfully'
                                    ); // تحقق من نجاح الإخفاء
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('خطأ', xhr.responseJSON.message ||
                                'حدث خطأ أثناء الاتصال بالخادم.');
                        }
                    });
                }
            });
        });

        //رسالة اختفاء تعديل قيد افتتاحي
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 5000); // الرسالة ستختفي بعد 5 ثواني (5000 ميلي ثانية)
    </script>
@endsection
