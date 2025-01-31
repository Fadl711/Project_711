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
        table th, table td {
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
        
        font-family: Arial, sans-serif; /* الخط الافتراضي */
    }
  
</style>
@props(['general_entries'])

<table class="w-full text-sm bg-white rounded-lg">
    <thead class="bg-gray-100">
    <tr>
        <th class="">ID</th>
        <th class="">رقم الفرعي</th>
        <th class="">رقم الرئيسي</th>
        <th class="">رقم القيد اليومي</th>
        <th class="">رقم الصفحة اليومية</th>
        <th class=""> المستخدم</th>
        <th class="">رقم الصفحة العامة</th>
        <th class="">معرف الفترة المحاسبية</th>
        <th class="">نوع القيد</th>
        <th class="">المبلغ</th>
        <th class=""> العملة</th>
        <th class="">نوع الفاتورة</th>
        <th class="">رقم الفاتورة</th>
        <th class="">الوصف</th>
        <th class="">تاريخ القيد</th>
        <th class="">معرف القيد</th>
    </tr>
</thead>
<tbody>

    @foreach($general_entries as $entry)
        <tr class=" bg-white">
            <td>{{ $entry['id'] }}</td>
<td>{{ $entry['subAccount'] ? $entry['subAccount']->sub_name : 'غير محدد' }}</td>
            <td>{{ $entry['Main_id'] }}</td>
            <td>{{ $entry['Daily_entry_id'] }}</td>
            <td>{{ $entry['Daily_Page_id'] }}</td>
            <td>{{ $entry['User_id'] ? $entry['User_id']->name : 'غير محدد' }}</td>
            <td>{{ $entry['General_ledger_page_number_id'] }}</td>
            <td>{{ $entry['accounting_period_id'] }}</td>
            <td>{{ $entry['entry_type'] }}</td>
            <td>{{ $entry['amount'] }}</td>
            <td>{{ $entry['Currency_name'] }}</td>
            <td>{{ $entry['Invoice_type'] }}</td>
            <td>{{ $entry['Invoice_id'] }}</td>
            <td>{{ $entry['description'] }}</td>
            <td>{{ $entry['entry_date'] }}</td>
            <td>{{ $entry['entrie_id'] }}</td>
        </tr>
    @endforeach
</tbody>
</table>