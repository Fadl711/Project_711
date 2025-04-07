@extends('layout')
@section('conm')
<link rel="preload" href="https://fonts.bunny.net/figtree/files/figtree-latin-400-normal.woff2" as="font" type="font/woff2" crossorigin>
<style>
    body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}
</style>
@if (session('success'))
<div id="success-message" class="alert alert-success">
    {{ session('success') }}
</div>
@endif


@if (session('error'))
<div id="success-danger" class="alert alert-danger">
{{ session('error') }}
</div>
@endif
<script>
// التأكد من وجود عنصر الرسالة
window.onload = function() {
    const message = document.getElementById('success-message');
    const messagedanger = document.getElementById('success-danger');

    if (message ) {
        setTimeout(() => {
            message.style.display = 'none'; // إخفاء الرسالة
        }, 3000); // 3000 مللي ثانية = 3 ثواني
    }
    if (messagedanger ) {
        setTimeout(() => {
            messagedanger.style.display = 'none'; // إخفاء الرسالة
        }, 3000); // 3000 مللي ثانية = 3 ثواني
    }
};
</script>
<div class="container mx-auto  shadow-sm bg-white flex">
    <div class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
        <form action="{{ route('git.pull') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary text-white">تحديث النظام</button>
        </form>
    </div>
    <div class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
        <form action="{{ route('route.clear') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger text-white">تحديث المسارات</button>
        </form>
    </div>
    {{-- <div class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
        <form action="{{ route('sync') }}" method="POST">
            @csrf
            <button  type="submit" class="btn btn-danger text-white" >رفع البيانات </button>
        </form>
    </div> --}}
{{-- 
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>استيراد بيانات المبيعات</h2>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
    
                <form action="{{ route('sales.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="file">اختر ملف CSV:</label>
                        <input type="file" name="file" class="form-control" required accept=".csv">
                        <small class="text-muted">يجب أن يحتوي الملف على العناوين الصحيحة في السطر الأول</small>
                    </div>
                    <button type="submit" class="btn btn-primary">استيراد البيانات</button>
                </form>
            </div>
        </div>
    </div> --}}
    {{-- <div class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
        <form action="{{ route('run_migration') }}" method="POST">
            @csrf
            <button  type="submit" class="btn  btn-success">تشغيل الترحيل</button>
        </form>
    </div> --}}
    {{-- <div class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
        <form action="{{ route('run_migration') }}" method="POST">
            @csrf
            <button  type="submit" class="btn  btn-success">تشغيل الترحيل</button>
        </form>
    </div> --}}
  
    {{-- <div class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
        <form action="{{ route('Saleupdate') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary text-white">تحديث قيود المبيعات </button>
        </form>
    </div> --}}
</div>


{{-- <textarea name="" id="" cols="30" rows="1">php artisan migrate</textarea> --}}
@endsection
