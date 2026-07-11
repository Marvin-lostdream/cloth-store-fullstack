<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // استقبال الطلب من localStorage
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

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_price' => 0
            ]);

            $total = 0;

            foreach ($items as $item) {
                $product = Product::find($item['id']);

                if (!$product) continue;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price
                ]);

                $total += $product->price * $item['quantity'];
            }

            $order->total_price = $total;
            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ تم إتمام الطلب بنجاح',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'خطأ: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
