@extends('layouts.app')

@php
$hideFooter = true;
@endphp

@push('style')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
@endpush

@section('title' , "CTS | لوحة التحكم")

@section('content')
<div class="products-table" dir="rtl">
    <div class="table-header">
        <h2>المنتجات</h2>
        <button onclick="openModal('addModal')">إضافة منتج جديد ➕</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الصورة</th>
                <th>السعر</th>
                <th>التصنيف</th>
                <th>النوع</th>
                <th>الحالة</th>
                <th>الخصم</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                    @else
                    <span style="color: #999;">لا توجد صورة</span>
                    @endif
                </td>
                <td>{{ number_format($product->price , 2) }} ل.س</td>
                <td>
                    @switch($product->category)
                    @case('men') رجالي @break
                    @case('women') نسائي @break
                    @case('kids') اطفال @break
                    @case('accessories') إكسسوارات @break
                    @default أخرى
                    @endswitch
                </td>
                <td>
                    @switch($product->type)
                    @case('shirts') قمصان @break
                    @case('pants') بناطيل @break
                    @case('dresses') فساتين @break
                    @case('shoes') أحذية @break
                    @case('jackets') جواكت @break
                    @case('bags') شنط @break
                    @case('watches') ساعات @break
                    @case('accessories') إكسسوارات @break
                    @default أخرى
                    @endswitch
                </td>
                <td><span class="status {{ $product->is_available ? 'active' : 'inactive' }}">
                        {{ $product->is_available ? 'متوفر' : 'غير متوفر' }}
                    </span>
                </td>
                <td>
                    @if($product->has_discount)
                    <span>✅ نعم</span>
                    @else
                    <span>❌ لا</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn edit" onclick="openEditModal('{{ $product->id }}')"><i class="fas fa-pencil-alt"></i></button>
                        <button class="action-btn delete" onclick="openDeleteModal('{{ $product->id }}', '{{ $product->name }}')"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
                    <p style="font-size: 18px;">📦 لا توجد منتجات</p>
                    <p>قم بإضافة منتج جديد بالضغط على الزر أعلاه</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ======================= -->
<!-- ====== Add Modal ====== -->
<!-- ======================= -->

<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>إضافة منتج</h2>
            <button class="close-btn" onclick="closeModal('addModal')">&times;</button>
        </div>
        <form action="{{ route('product.create') }}" id="addForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>اسم المنتج</label>
                <input type="text" name="name" required />
            </div>
            <div class="form-group">
                <label>سعر المنتج (ل.س)</label>
                <input type="number" name="price" step="0.01" required />
            </div>
            <div class="form-group">
                <label>رابط الصورة (Google Drive / ImgBB)</label>
                <input type="url" name="image" placeholder="https://i.ibb.co/xxx/image.jpg" required />
                <small style="color: #666; display: block; margin-top: 5px;">📸 أدخل الرابط المباشر للصورة من ImgBB أو Google Drive</small>
            </div>
            <div class="form-group">
                <label>التوفر</label>
                <select name="is_available">
                    <option value="1">متوفر</option>
                    <option value="0">غير متوفر</option>
                </select>
            </div>
            <div class="form-group">
                <label>التصنيف</label>
                <select name="category">
                    <option value="men">رجالي</option>
                    <option value="women">نسائي</option>
                    <option value="kids">اطفال</option>
                    <option value="accessories">إكسسوارات</option>
                    <option value="other">أخرى</option>
                </select>
            </div>
            <div class="form-group">
                <label>نوع المنتج</label>
                <select name="type">
                    <option value="shirts">قمصان</option>
                    <option value="pants">بناطيل</option>
                    <option value="dresses">فساتين</option>
                    <option value="shoes">أحذية</option>
                    <option value="jackets">جواكت</option>
                    <option value="bags">شنط</option>
                    <option value="watches">ساعات</option>
                    <option value="accessories">إكسسوارات</option>
                    <option value="other">أخرى</option>
                </select>
            </div>
            <div class="form-group">
                <label>هل يوجد خصم</label>
                <select name="has_discount">
                    <option value="0">لا</option>
                    <option value="1">نعم</option>
                </select>
            </div>
            <button type="submit" class="btn-submit">إضافة منتج</button>
        </form>
    </div>
</div>

<!-- ======================= -->
<!-- ====== Edit Modal ====== -->
<!-- ======================= -->

<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>تعديل المنتج</h2>
            <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="id">
            <div class="form-group">
                <label>اسم المنتج</label>
                <input type="text" name="name" id="edit_name" required />
            </div>
            <div class="form-group">
                <label>سعر المنتج (ل.س)</label>
                <input type="number" name="price" step="0.01" id="edit_price" required />
            </div>
            <div class="form-group">
                <label>الصورة الحالية</label>
                <img id="edit_current_img" src="" alt="الصورة الحالية"
                    style="max-width: 150px; max-height: 150px; object-fit: cover; border-radius: 5px; display: none;">
                <label style="display: block; margin-top: 10px;">تغيير رابط الصورة (اختياري)</label>
                <input type="url" name="image" id="edit_image" placeholder="https://i.ibb.co/xxx/image.jpg" />
                <small style="color: #666; display: block; margin-top: 5px;">📸 أدخل الرابط المباشر للصورة الجديدة (اختياري)</small>
            </div>
            <div class="form-group">
                <label>التوفر</label>
                <select name="is_available" id="edit_is_available">
                    <option value="1">متوفر</option>
                    <option value="0">غير متوفر</option>
                </select>
            </div>
            <div class="form-group">
                <label>التصنيف</label>
                <select name="category" id="edit_category">
                    <option value="men">رجالي</option>
                    <option value="women">نسائي</option>
                    <option value="kids">اطفال</option>
                    <option value="accessories">إكسسوارات</option>
                    <option value="other">أخرى</option>
                </select>
            </div>
            <div class="form-group">
                <label>نوع المنتج</label>
                <select name="type" id="edit_type">
                    <option value="shirts">قمصان</option>
                    <option value="pants">بناطيل</option>
                    <option value="dresses">فساتين</option>
                    <option value="shoes">أحذية</option>
                    <option value="jackets">جواكت</option>
                    <option value="bags">شنط</option>
                    <option value="watches">ساعات</option>
                    <option value="accessories">إكسسوارات</option>
                    <option value="other">أخرى</option>
                </select>
            </div>
            <div class="form-group">
                <label>هل يوجد خصم</label>
                <select name="has_discount" id="edit_has_discount">
                    <option value="0">لا</option>
                    <option value="1">نعم</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn-submit">تحديث المنتج</button>
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">إلغاء</button>
            </div>
        </form>
    </div>
</div>

<!-- ======================= -->
<!-- ====== Delete Modal ====== -->
<!-- ======================= -->

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>تأكيد الحذف</h2>
            <button class="close-btn" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div style="text-align:center; display: flex; justify-content:center; flex-direction: column;">
            <p style="font-size:18px; margin:20px 0;">هل انت متأكد من حذف المنتج <strong id="delete_product_name"></strong>?</p>
            <form dir="auto" id="deleteForm" action="" method="POST" style="display: flex; justify-content: space-between; align-items:center">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-confirm">نعم , احذف المنتج</button>
                <button type="button" class="btn-cancel" onclick="closeModal('deleteModal')">إلغاء</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
