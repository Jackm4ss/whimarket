<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Show the change password form.
     */
    public function edit(): View
    {
        return view('auth.change-password', [
            'title' => 'Ganti Password | WhiMarket',
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $hasExistingPassword = ! empty($user->password);

        $rules = [
            'password' => ['required', 'confirmed', Password::min(8)],
        ];

        $messages = [
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
        ];

        if ($hasExistingPassword) {
            $rules['current_password'] = ['required', 'current_password'];
            $messages['current_password.required'] = 'Password saat ini wajib diisi.';
            $messages['current_password.current_password'] = 'Password saat ini tidak sesuai.';
        }
        $validated = $request->validate($rules, $messages);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/akun/password')->with('success', 'Password Anda berhasil diperbarui! Silakan gunakan password baru ini untuk sesi berikutnya.');
    }
}
