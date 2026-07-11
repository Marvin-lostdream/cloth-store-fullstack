<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
public function checkout(Request $request)
{
    try {
        $items = $request->items;

        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'السلة فارغة']);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'يرجى تسجيل الدخول أولاً'], 401);
        }

        // اختبار بسيط: هل يمكن إنشاء طلب؟
        $testOrder = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الطلب التجريبي بنجاح',
            'order_id' => $testOrder->id
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطأ: ' . $e->getMessage()
        ], 500);
    }
}
}
