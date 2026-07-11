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
    public function createProduct(Request $request)
    {
        try {
            $product = new Product();
            $product->name = $request->name;
            $product->price = $request->price;
            $product->image = $request->image;
            $product->category = $request->category;
            $product->type = $request->type;
            $product->is_available = $request->boolean('is_available', true);
            $product->has_discount = $request->boolean('has_discount', false);


            $product->save();

            return redirect('admin/dashboard')->with('success', "تمت إضافة المنتج {$request->name} بنجاح");
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


    public function editProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $product->name = $request->name;
            $product->price = $request->price;
            $product->category = $request->category;
            $product->type = $request->type;
            $product->is_available = $request->boolean('is_available', true);
            $product->has_discount = $request->boolean('has_discount', false);

            if ($request->filled('image')) {
                $product->image = $request->image;
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

            $product->delete();

            return redirect('admin/dashboard')->with('success', "تم حذف المنتج بنجاح");
        } catch (Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء الحذف: ' . $e->getMessage());
        }
    }
}
