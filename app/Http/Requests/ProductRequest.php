<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        return $user->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable'],
            'is_available' => ['boolean'],
            'price' => ['required', 'numeric', 'min:0'],
            'has_discount' => ['boolean'],
            'category' => ['required', 'in:men,women,kids,accessories,other'],
            'type' => ['required', 'in:shirts,pants,dresses,shoes,jackets,bags,watches,accessories,other'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المنتج مطلوب',
            'name.max' => 'اسم المنتج لا يتجاوز 255 حرف',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر لا يمكن أن يكون سالب',
            'category.required' => 'التصنيف مطلوب',
            'category.in' => 'التصنيف غير صحيح',
            'image.image' => 'الملف يجب أن يكون صورة',
            'image.mimes' => 'الصيغ المدعومة: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة لا يتجاوز 2MB',
        ];
    }
}
