@extends('layout')
@section('conm')
<div class="container mx-auto ">
    <!-- Search and Filter Section -->
    <div class="bg-white p-1 shadow-lg rounded-lg flex flex-col sm:flex-row items-center gap-4 justify-between mb-2">
        <div class="w-full sm:w-auto flex gap-4 items-center">
            <select class="border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500">
                <option selected>كل الفواتير</option>
                <option>أول فاتورة</option>
                <option>آخر فاتورة</option>
            </select>
            <input type="text" class="border border-gray-300 rounded-lg p-2 w-full sm:w-auto focus:ring-2 focus:ring-indigo-500" placeholder="بحث" name="search">
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">تحديث البيانات</button>
    </div>
    <!-- Date Filter Section -->
    <form class="bg-gray-50 p-1 rounded-lg shadow-md mb-2">
        <ul class="flex flex-col sm:flex-row gap-4 items-center">
            <li class="w-full text-center">
                <label class="text-sm font-medium">عرض حسب</label>
            </li>
            <li class="w-full text-center">
                <input type="radio" name="list-radio" checked class="mr-2"> تلقائي
            </li>
            <li class="w-full text-center">
                <input type="radio" name="list-radio" class="mr-2"> اليوم
            </li>
            <li class="w-full text-center">
                <input type="radio" name="list-radio" class="mr-2"> هذا الأسبوع
            </li>
            <li class="w-full text-center">
                <input type="radio" name="list-radio" class="mr-2"> هذا الشهر
            </li>
            <li class="w-full flex items-center justify-center">
                <label class="text-sm font-medium">من:</label>
                <input type="date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
            </li>
            <li class="w-full flex items-center justify-center">
                <label class="text-sm font-medium">إلى:</label>
                <input type="date" class="mx-2 p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
            </li>
        </ul>
    </form>
    <div class="overflow-y-auto max-h-[80vh] bg-white px-4 py-1 rounded-lg shadow-md">
        @foreach ($purchaseInvoices as $purchase)
            <div class="mb-3 border border-gray-300 rounded-lg px-4 py-2 shadow-sm">
                <div class="flex justify-between items-center">
                   <div class="text-right">
                    <div class="text-gray-700 text-right">
                        تاريخ الفاتورة: <span class="text-sm">{{ $purchase['formatted_date'] }}</span>
                    </div>
                    <div class="text-gray-700 text-right">
                        اسم {{ $purchase['main_account_class'] }}: <span class="text-sm">{{ $purchase['supplier_name'] }}</span>
                    </div>
                    </div>
                    <div class="text-gray-700">
                        فاتورة {{ $purchase['transaction_type'] }}: <span class="text-sm">{{ $purchase['Invoice_type'] }}</span>
                    </div>
                    <div class="text-right">
                        <div>رقم الفاتورة: <span class="text-sm">{{ $purchase['invoice_number'] }}</span></div>
                        <div>رقم الإيصال: <span class="text-sm">{{ $purchase['receipt_number'] }}</span></div>
                    </div>
                </div>
                <table class="w-full text-center border-collapse text-sm">
                    <thead class="bg-indigo-600 text-white text-sm">
                        <tr>
                            <th class="py-1 px-4">إجمالي تكلفة الفاتورة</th>
                            <th class="py-1 px-4">إجمالي المصاريف</th>
                            <th class="py-1 px-4">المدفوع</th>
                            <th class="py-1 px-4">عرض الفاتورة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-gray-50">
                            <td class="py-1 px-4">{{ $purchase['total_invoice'] }}</td>
                            <td class="py-1 px-4">{{ $purchase['total_cost'] }}</td>
                            <td class="py-1 px-4">{{ $purchase['paid'] }}</td>
                            <td class="py-1 px-4">
                                <button value="{{ $purchase['invoice_number'] }}" onclick="openInvoiceWindow(event)" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">فتح الفاتورة</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="grid grid-cols-2">
                    <div class="text-gray-700 text-sm text-right">
                        المستخدم: <span>{{ $purchase['user_name'] }}</span>
                    </div>
                    <div class="text-gray-700 text-left text-sm">
                        تاريخ التحديث: <span>{{ $purchase['updated_at'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function openInvoiceWindow(e) {
        // استخدم e.target للحصول على الزر الذي تم النقر عليه
        let invoiceField = $(e.target).val(); // استخدام e.target بدلاً من this

        const successMessage = $('#successMessage');
        
        if (invoiceField) {
            e.preventDefault(); // منع تحديث الصفحة

            // استبدال القيمة في الرابط
            const url = `{{ route('invoicePurchases.print', ':invoiceField') }}`.replace(':invoiceField', invoiceField);

            window.open(url, '_blank', 'width=600,height=800'); // فتح الرابط مع استبدال القيمة
        } else {
            successMessage.text('لا توجد فاتورة').show();
            setTimeout(() => {
                successMessage.hide();
            }, 3000);
        }
    }
</script>

@endsection 

