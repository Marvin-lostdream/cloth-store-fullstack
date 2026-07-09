<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{



    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ], [
            'email.required' => "يرجى كتابة البريد الإلكتروني",
            'email.email' => "يرجى كتابة بريد إلكتروني صحيح",
            'password.required' => "كلمة المرور مطلوبة",
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

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();


            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "تم تسجيل الدخول بنجاح",
                    'user' => Auth::user()->only(['id', 'name', 'email', 'role'])
                ]);
            }



            if (Auth::user()->isAdmin()) {
                return redirect()->route('dashboard');
            }

            return redirect()->intended('/');
        }

        return redirect()->back()->withErrors(['email' => "البريد الإلكتروني او كلمة المرور غير صحيحة"])->withInput($request->except('password'));
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "تم تسجيل الخروج بنجاح",
                'clear_cart' => true
            ]);
        }


        return redirect('/');
    }
}
