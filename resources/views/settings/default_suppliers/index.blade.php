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
            <button type="submit" class="btn btn-danger">تحديث المسارات</button>
        </form>
    </div>
    <div class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded">
        <form action="{{ route('git.pull') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">تحديث المشروع</button>
        </form>
    </div>
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    <form action="{{route('send.sms')}}" method="POST">
        @csrf
        <label for="to">رقم الهاتف:</label>
        <input type="text" id="to" name="to" required>
        <br>
        <label for="message">الرسالة:</label>
        <textarea id="message" name="message" required></textarea>
        <br>
        <button type="submit" class="btn btn-primary">إرسال </button>

        {{-- <button type="submit">إرسال</button> --}}
    </form>
</div>
@endsection
