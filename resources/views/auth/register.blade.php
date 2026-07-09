@extends('layouts.app')



@push('style')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endpush

@section('title' , "CTS | تسجيل مستخدم جديد")


@section('content')

<div class="register">
    <div class="re-container">
        <ul class="sections">
            <li>
                <a class="active" href="{{ route('register') }}">مستخدم جديد</a>
            </li>
            <li>
                <a href="{{ route('login') }}">تسجيل دخول</a>
            </li>
        </ul>
        <form class="register-form" action="/register" method="post">
            @csrf
            <div>
                <label for="name">الاسم : </label>
                <input type="text" name="name" id="register-name" placeholder="الاسم..." required>
            </div>
            <div>
                <label for="email">الإيميل : </label>
                <input type="email" name="email" id="register-email" placeholder="الإيميل..." required>
            </div>
            <div>
                <label for="password">كلمة المرور : </label>
                <input type="password" name="password" id="register-password" placeholder="كلمة المرور يجب ان تحتوي على 8 أحرف على الأقل..." required>
            </div>
            <div>
                <label for="password">تأكيد كلمة المرور : </label>
                <input type="password" id="password_confirmation" placeholder="تأكيد كلمة المرور" required>
            </div>
            <button id="register-Btn" class="subBtn" type="submit">تسجيل مستخدم جديد</button>
        </form>
    </div>
</div>


@endsection

@push('script')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush