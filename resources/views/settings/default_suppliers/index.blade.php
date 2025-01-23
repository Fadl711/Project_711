@extends('layout')
@section('conm')
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
        <form action="{{ route('route.clear') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger text-white">تحديث المسارات</button>
        </form>
    </div>
    <div class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
        <form action="{{ route('git.pull') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary text-white">تحديث المشروع</button>
        </form>
    </div>

</div>
@endsection
