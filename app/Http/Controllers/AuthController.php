<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BehaviorAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request, BehaviorAnalyticsService $analytics)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email chưa đúng định dạng.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
            ]
        );

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Thông tin đăng nhập không đúng.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        $analytics->record('login', [
            'user_id' => $request->user()->id,
            'session_id' => $request->session()->get('tracking_session_id'),
        ]);

        return redirect()->intended($request->user()->isAdmin() ? route('admin.dashboard') : route('home'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['required', 'string', 'regex:/^(0|\+84)[0-9]{9,10}$/'],
                'password' => ['required', 'confirmed', Password::min(6)],
            ],
            [
                'name.required' => 'Vui lòng nhập họ và tên.',
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email chưa đúng định dạng.',
                'email.unique' => 'Email này đã được sử dụng.',
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'phone.regex' => 'Số điện thoại chưa hợp lệ (ví dụ: 09xxxxxxxx hoặc +84xxxxxxxxx).',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.confirmed' => 'Xác nhận mật khẩu chưa khớp.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            ]
        );

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
