@extends('layouts.app')


@push('style')
<link rel="stylesheet" href="{{ asset('css/categories.css') }}" />
@endpush

@section('title' , "CTS | القسم الرجالي")


@section('content')
<div class="title">
    <h1>قسم الرجالي</h1>
    <p>كافة انواع الألبسة والبدلات الخاصة بالرجال</p>
</div>
<section class="category">
    <nav class="all-sections">
        <h3>: كافة الأقسام</h3>
        <ul>
            <li class="active men"><a href="{{ route('men') }}">قسم الرجال</a></li>
            <li class="women"><a href="{{ route('women') }}">قسم النساء</a></li>
            <li class="kids"><a href="{{ route('kids') }}">قسم الاطفال</a></li>
            <li class="accessories">
                <a href="{{ route('accessories') }}">قسم الإكسسوارات</a>
            </li>
        </ul>
    </nav>
    <div class="added"></div>
    <div class="container">
        <div class="clothes">
            <h3>: ملابس رجالية</h3>
            <ul>
                <li data-section="shoes">أحذية</li>
                <li data-section="other">للمناسبات</li>
                <li data-section="pants">بناطيل</li>
                <li class="active" data-section="shirts">قمصان</li>
            </ul>
        </div>
        <div id="shContainer" class="shContainer">
            <p>: خانة البحث</p>
            <input
                dir="rtl"
                type="search"
                name="search"
                id="search"
                placeholder="ابحث هنا..." />
        </div>
        <div class="products" data-category="men"></div>
    </div>
</section>
@endsection


@push('script')
<script src="{{ asset('js/categories.js') }}"></script>
@endpush