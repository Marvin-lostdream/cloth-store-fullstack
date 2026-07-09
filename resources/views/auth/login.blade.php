@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endpush

@section('title' , "CTS | تسجيل دخول")


@section('content')

<div class="login">
    <div class="re-container">
        <ul class="sections">
            <li>
                <a href="{{ route('register') }}">مستخدم جديد</a>
            </li>
            <li>
                <a class="active" href="{{ route('login') }}">تسجيل دخول</a>
            </li>
        </ul>
        <form class="login-form" action="/login" method="post">
            @csrf
            <div>
                <label for="email">الإيميل : </label>
                <input type="email" name="email" id="login-email" placeholder="الإيميل...">
            </div>
            <div>
                <label for="password">كلمة المرور : </label>
                <input type="password" name="password" id="login-password" placeholder="أدخل كلمة المرور...">
            </div>
            <button id="login-Btn" class="subBtn" type="submit">تسجيل الدخول</button>
        </form>
    </div>
</div>


@endsection

@push('script')
<script src="{{ asset('js/auth.js') }}"></script>
@endpush