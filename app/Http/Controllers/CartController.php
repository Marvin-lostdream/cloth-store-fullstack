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
            Log::info('Checkout started', ['items' => $request->items]);

            $items = $request->items;

            if (empty($items)) {
                return response()->json(['success' => false, 'message' => 'السلة فارغة']);
            }

            $user = Auth::user();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'يرجى تسجيل الدخول أولاً'], 401);
            }

            Log::info('User authenticated', ['user_id' => $user->id]);

            DB::beginTransaction();

            // تحقق من وجود الموديلات
            if (!class_exists(Order::class)) {
                throw new \Exception('Order model not found');
            }

            Log::info('Creating order...');

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_price' => 0
            ]);

            Log::info('Order created', ['order_id' => $order->id]);

            $total = 0;

            foreach ($items as $item) {
                Log::info('Processing item', ['item' => $item]);

                $product = Product::find($item['id']);

                if (!$product) {
                    Log::warning('Product not found', ['product_id' => $item['id']]);
                    continue;
                }

                Log::info('Product found', [
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'name' => $product->name
                ]);

                // تحقق من وجود السعر
                if (!isset($product->price)) {
                    throw new \Exception('Product price is null for product ID: ' . $product->id);
                }

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

            Log::info('Checkout completed successfully', ['order_id' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => '✅ تم إتمام الطلب بنجاح',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Checkout error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطأ: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
