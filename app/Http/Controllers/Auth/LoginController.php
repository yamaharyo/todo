<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Показ формы для входа пользователей.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработка попытки входа.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('todos.index'));
        }

        return back()->withErrors([
            'email' => 'Указанные учетные данные не совпадают с нашими записями.',
        ])->onlyInput('email');
    }

    /**
     * Выход пользователя из системы.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
