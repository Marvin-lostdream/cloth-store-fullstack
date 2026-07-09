@extends('layouts.app')


@section('title' , "CTS | الصفحة الرئيسية")


@section('content')
<div class="landing">
    <div class="info">
        <h2>
            <span class="cursor">|</span>
            <span id="typewriter"></span>
        </h2>
        <span class="tagline"></span>
        <p>... تسوق الآن</p>
        <a class="start">ابدأ تجربتك</a>
    </div>
</div>
<section class="categories" aria-labelledby="categories-title">
    <h2 class="categories-title">تصفح الأقسام</h2>
    <div class="container">
        <div class="category-card men">
            <h3>قسم الرجالي</h3>
            <div>
                <img src="{{ asset('img/categoryMen.png') }}" alt="ملابس رجالية" loading="lazy" />
            </div>
            <p>مجموعة من أرقى انواع الملابس الرجالية</p>
            <a class="man" href="{{ route('men') }}">تصفح الآن</a>
        </div>
        <div class="category-card women">
            <h3>قسم النسائي</h3>
            <div>
                <img
                    src="{{ asset('img/categoryWoman.png') }}"
                    alt="ملابس نسائية"
                    loading="lazy" />
            </div>
            <p>مجموعة من أرقى انواع الملابس النسائية</p>
            <a class="woman" href="{{ route("women") }}">تصفح الآن</a>
        </div>
        <div class="category-card kids">
            <h3>قسم الأطفال</h3>
            <div>
                <img src="{{ asset('img/categoryKids.png') }}" alt="ملابس أطفال" loading="lazy" />
            </div>
            <p>مجموعة من أرقى انواع الملابس الأطفال</p>
            <a class="kids" href="{{ route("kids") }}">تصفح الآن</a>
        </div>
        <div class="category-card acc">
            <h3>قسم الإكسسوارت</h3>
            <div>
                <img
                    src="{{ asset('img/categoryAccessories.png') }}"
                    alt="إكسسوارات"
                    loading="lazy" />
            </div>
            <p>مجموعة من أرقى انواع الإكسسوارات</p>
            <a class="accesories" href="{{ route("accessories") }}">تصفح الآن</a>
        </div>
    </div>
</section>

@endsection
