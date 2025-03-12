@extends('layout')
@section('conm')
@if(session('success'))
    <div id="success-message" class="bg-green-500 text-white p-4 rounded-md mb-4 text-center">
        {{ session('success') }}
    </div>
@endif
<div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- نسخ احتياطي -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">   نسخ قاعدة البيانات للجهاز</h1>
            <form action="{{ route('backup') }}" method="POST" id="backupForm">
                @csrf
                <div class="mb-4">
                </div>
                <button type="submit" id="backupBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-md font-medium">
                    إنشاء نسخة احتياطية
                </button>
            </form>
            <br>
            <br>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">نسخ قاعدة البيانات لجوجل درايف</h1>
            <form action="{{ route('backup.google') }}" method="get" enctype="multipart/form-data" class=" mx-auto p-6 bg-white shadow-md rounded-lg">
                @csrf
                <div class="mb-4">
                </div>
                <button type="submit" id="backupBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-md font-medium">
                    إنشاء نسخة احتياطية
                </button>
            </form>

        </div>




<form action="{{ url('restore') }}" method="POST" enctype="multipart/form-data" class=" mx-auto p-6 bg-white shadow-md rounded-lg">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">استعادة قاعدة البيانات</h1>

    @csrf
    <div class="mb-4">
        <label for="database_file" class="block text-gray-700 text-sm font-bold mb-2">
            📂 اختر ملف قاعدة البيانات:
        </label>
        <input type="file" name="database_file" id="database_file" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>
    <div class="flex justify-end">
        <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            🚀 استعادة قاعدة البيانات
        </button>
    </div>
</form>


<script>
    // انتظر حتى يتم تحميل الصفحة بالكامل
    document.addEventListener('DOMContentLoaded', function() {
        // احصل على عنصر الرسالة
        const successMessage = document.getElementById('success-message');

        // إذا كان العنصر موجودًا
        if (successMessage) {
            // أخفِ الرسالة بعد 5 ثوانٍ (5000 مللي ثانية)
            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 5000); // 5000 مللي ثانية = 5 ثوانٍ
        }
    });
</script>
@endsection
