<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{



    public function getProductsByCategory($category, $subcategory = null)
    {

        $query = product::where('category', $category);

        if ($subcategory) {
            $query->where('type', $subcategory);
        }

        $products = $query->get();

        return response()->json([
            'products' => $products
        ]);
    }


















    // Admin

    // عرض المنتجات في لوحة التحكم بترتيب يعتمد على الأحدث أولا
    public function dashboard()
    {
        $products = product::orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('products'));
    }






    //  إضافة منتج جديد لقاعدة البيانات
    public function createProduct(ProductRequest $request)
    {

        try {
            $product = product::create([
                ...$request->validated(),
                'image' =>
                $request->hasFile('image') ?
                    $request->file('image')->store('products', 'public')
                    : null,
                'is_available' => $request->boolean('is_available', true),
                'has_discount' => $request->boolean('has_discount', false),
            ]);
            return redirect('admin/dashboard')->with('success', "تمت إضافة المنتج {$request->input('name')} بنجاح");
        } catch (Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function getProductForEdit($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json(['error' => 'المنتج غير موجود'], 404);
        }
    }


    public function editProduct(ProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validated();

            $product->update($validated);


            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }

            $product->save();

            return redirect('admin/dashboard')->with('success', "تم تحديث المنتج {$product->name} بنجاح");
        } catch (Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء التحديث: ' . $e->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);

            // حذف الصورة من التخزين
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // حذف المنتج
            $product->delete();

            return redirect('admin/dashboard')->with('success', "تم حذف المنتج بنجاح");
        } catch (Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
