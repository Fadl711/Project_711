// دالة لحساب الربح
function Profitproduct() {
    var purchase_price = parseFloat($('#Purchase_price').val()); // الحصول على قيمة سعر الشراء
    var selling_price = parseFloat($('#Selling_price').val());   // الحصول على قيمة سعر البيع
    // التأكد من أن القيم المدخلة صحيحة
    if (selling_price > 0 && purchase_price > 0) {
        var profit = selling_price - purchase_price; // حساب الربح
        $('#Profit').val(profit).trigger('change'); // عرض الربح مع تقريب إلى خانتين عشريتين
    } else {
        $('#Profit').val(''); // تفريغ الحقل في حال وجود قيم غير صالحة
    }
}

$('#Purchase_price, #Selling_price').on('input', function() {
    Profitproduct(); // بدء الحساب عند تغيير القيم في الحقول
});


// دالة لحساب السعر ال��جمالي

function TotalPrice() {
    var price = parseFloat($('#Purchase_price').val()); // الحصول على القيمة التي تم إدخالها في الحقل
    var quantity = parseInt($('#Quantity').val()); // الحصول على الكمية التي تم إدخالها في الحقل
    // التأكد من أن القيم المدخلة صحيحة
    if (price > 0 && quantity > 0) {
        var total_price = price * quantity; // حساب السعر الإجمالي
        $('#Total').val(total_price).trigger('change'); // عرض السعر الإجمالي مع تقريب إلى خانت
    }
    else {
        $('#Total').val(''); // تفريغ الحقل في حال وجود قيم ��ير ��الحة
    }
}

$('#Purchase_price, #Quantity').on('input', function() {
    TotalPrice(); // بد�� الحساب عند تغيير القيم في الحقول
});

// دالة لحساب التكلفة المتكررة

function RepeatedCost() {
    var total_cost = parseFloat($('#Total_cost').val()); // الحصول على القيمة المدخلة كعدد عشري
    var purchase_price = parseFloat($('#Purchase_price').val()); // الحصول على سعر الشراء
    // التأكد من أن السعر الإجمالي وسعر الشراء أرقام صالحة لتجنب قسمة على صفر أو أخطاء
    if (!isNaN(total_cost) && !isNaN(purchase_price) && purchase_price > 0) {
        var cost = total_cost / purchase_price; // حساب التكلفة
        $('#Cost').val(cost); // إضافة السعر إلى الحقل مع تقريبه إلى خانتين عشريتين
    } else {
        $('#Cost').val(''); // في حال وجود خطأ في المدخلات، يتم تفريغ الحقل
    }
}

$('#Total_cost, #Purchase_price').on('input', function() {
    RepeatedCost(); // بد�� الحساب عند تغيير القيم في الحقول
});
$(document).ready(function() {
    $('.select2').select2();
});




