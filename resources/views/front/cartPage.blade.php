@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}" />
<link rel="stylesheet" href="{{ asset('css/categories.css') }}" />
@endpush

@section('title' , "CTS | السلة")

@section('content')
<div class="title">
    <h1>السلة</h1>
</div>

<section class="cartContainer">
    <div class="allProducts">
        <h2>السلة</h2>
        <div class="products">
            <!-- هنا يتم عرض المنتجات عن طريق الجافاسكربت -->
        </div>
    </div>
    <div class="total-price">
        <h2>الفاتورة</h2>
        <div class="total"></div>
        <button class="payBtn">دفع الفاتورة</button>
    </div>
</section>
@endsection

@push('script')
<script src="{{ asset('js/cart.js') }}"></script>
@endpush