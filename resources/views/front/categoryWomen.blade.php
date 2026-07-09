@extends('layouts.app')


@push('style')
<link rel="stylesheet" href="{{ asset('css/categories.css') }}" />
@endpush

@section('title' , "CTS | القسم النسائي")


@section('content')

<div class="title">
    <h1>قسم النسائي</h1>
    <p>كافة انواع الفساتين والبدلات الخاصة بالنساء</p>
</div>
<section class="category">
    <nav class="all-sections">
        <h3>: كافة الأقسام</h3>
        <ul>
            <li class="men"><a href="{{ route('men') }}">قسم الرجال</a></li>
            <li class="active women"><a href="{{ route('women') }}">قسم النساء</a></li>
            <li class="kids"><a href="{{ route('kids') }}">قسم الاطفال</a></li>
            <li class="accessories">
                <a href="{{ route('accessories') }}">قسم الإكسسوارات</a>
            </li>
        </ul>
    </nav>
    <div class="added"></div>
    <div class="container">
        <div class="clothes">
            <h3>: ملابس نسائية</h3>
            <ul>
                <li data-section="other">اخرى</li>
                <li data-section="shoes">أحذية</li>
                <li class="active" data-section="shirts">ملابس نسائية</li>
            </ul>
        </div>
        <div class="shContainer">
            <p>: خانة البحث</p>
            <input
                dir="rtl"
                type="search"
                name="search"
                id="search"
                placeholder="ابحث هنا..." />
        </div>
        <div class="products" data-category="women"></div>
    </div>
</section>
@endsection


@push('script')
<script src="{{ asset('js/categories.js') }}"></script>
@endpush
