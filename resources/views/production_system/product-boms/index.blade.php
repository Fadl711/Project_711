@extends('production_system.index')
@section('productionSystem')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قوائم المواد (BOMs)</h5>
            <a href="{{ route('product-boms.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إضافة BOM جديد
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="bomsTable">
                    <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>المنتج النهائي</th>
                            <th>المادة الخام</th>
                            <th class="text-center">الكمية</th>
                            <th>وحدة القياس</th>
                            <th class="text-center">نسبة الهدر</th>
                            <th class="text-center">التكلفة المعيارية</th>
                            <th class="text-center">الحالة</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($boms as $index => $bom)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $bom->product->product_name ?? 'غير معروف' }}</td>
                            <td>{{ $bom->material->product_name ?? 'غير معروف' }}</td>
                            <td class="text-center quantity">{{ $bom->quantity }}</td>
                            <td>{{ $bom->unit->Categorie_name ?? 'غير معروف' }}</td>
                            <td class="text-center waste-factor">{{ $bom->waste_factor }}</td>
                            <td class="text-center standard-cost">{{ $bom->standard_cost }}</td>
                            <td class="text-center">
                                <span class="badge badge-{{ $bom->is_active ? 'success' : 'danger' }}">
                                    {{ $bom->is_active ? 'مفعل' : 'غير مفعل' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('product-boms.edit', $bom->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('product-boms.destroy', $bom->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $boms->links() }}
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // دالة لتنسيق الأرقام
    function formatNumber(num, decimals = 2) {
        const number = parseFloat(num);
        if (isNaN(number)) return num;
        
        // التقريب إلى الخانات العشرية المطلوبة
        const rounded = number.toFixed(decimals);
        
        // إزالة الأصفار الزائدة بعد الفاصلة
        const formatted = rounded.replace(/\.?0+$/, '');
        
        // إضافة فواصل الآلاف
        return formatted.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // تطبيق التنسيق على جميع الأرقام في الجدول
    $('#bomsTable').find('.quantity').each(function() {
        $(this).text(formatNumber($(this).text(), 3));
    });
    
    $('#bomsTable').find('.waste-factor').each(function() {
        $(this).text(formatNumber($(this).text(), 2) + '%');
    });
    
    $('#bomsTable').find('.standard-cost').each(function() {
        $(this).text(formatNumber($(this).text(), 5));
    });
});
</script>
@endsection