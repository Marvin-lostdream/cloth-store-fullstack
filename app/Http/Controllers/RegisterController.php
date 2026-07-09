<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ], [
            'name.required' => "الاسم مطلوب",
            'email.required' => "البريد الإلكتروني مطلوب",
            'email.email' => "يرجى إدخال بريد إلكتروني صحيح",
            'email.unique' => "هذا البريد الإلكتروني مستخدم بالفعل",
            'password.required' => "يرجى إدخال كلمة المرور",
            'password.min' => "كلمة المرور يجب ان تكون من 8 احرف على الأقل",
            'password.confirmed' => "كلمات المرور غير متطابقة",
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }


            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => 'user'
            ]);

            Auth::login($user);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "تم التسجيل بنجاح",
                    'user' => $user->only(['id', 'name', 'email', 'role'])
                ], 201);
            }


            return redirect('/')->with("success", "تم تسجيل مستخدم جديد");
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "حدث خطأ اثناء التسجيل"
                ], 500);
            }

            return redirect()->back()->withErrors(["errors" => "حدث خطأ اثناء التسجيل"])->withInput();
        }
    }
}
