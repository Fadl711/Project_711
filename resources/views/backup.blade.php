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
            <h1 class="text-2xl font-bold text-gray-800 mb-4">نسخ قاعدة البيانات</h1>
            <form action="{{ route('backup') }}" method="POST" id="backupForm">
                @csrf
                <div class="mb-4">
                    <label for="path" class="block text-gray-700 font-medium mb-2">مسار حفظ النسخة الاحتياطية:</label>
                    <div class="flex">
                        <input type="text" id="path" name="path" class="form-input mt-1 block w-full rounded-md border-gray-300" required>
                        <button type="button" onclick="openFilePicker()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mr-2">اختر مسار</button>
                    </div>
                </div>
                <button type="submit" id="backupBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-md font-medium">
                    إنشاء نسخة احتياطية
                </button>
                <div id="backupLoading" class="hidden text-center mt-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600">جاري إنشاء النسخة الاحتياطية...</p>
                </div>
            </form>
        </div>

    <input type="file" id="filePicker" webkitdirectory style="display: none;" onchange="setPath()">

    <script>
        function openFilePicker() {
            document.getElementById('filePicker').click();
        }

        function setPath() {
            var input = document.getElementById('filePicker');
            var path = input.files[0].path; // الحصول على اسم الملف
            document.getElementById('path').value = path; // تعيين اسم الملف في حقل الإدخال
        }

        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 5000);  // الرسالة ستختفي بعد 5 ثواني (5000 ميلي ثانية)

    </script>
@endsection
