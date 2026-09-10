<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        if ($request->filled('role')) {
            session(['auth_role' => $request->query('role')]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Gagal login via Google. Silakan coba kembali.');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        $isNewUser = false;

        if (! $user) {
            $user = User::create([
                'google_id' => $googleUser->getId(),
                'name' => $googleUser->getName() ?? 'Pengguna WhiMarket',
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
                'role' => UserRole::BUYER,
            ]);

            $user->assignRole('buyer');
            $isNewUser = true;
        } else {
            // Update google_id and avatar if missing
            $user->update([
                'google_id' => $user->google_id ?? $googleUser->getId(),
                'avatar' => $user->avatar ?? $googleUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        // Check if buyer has addresses
        if ($isNewUser || ! $user->addresses()->exists()) {
            session()->flash('show_onboarding_modal', true);
        }
        $requestedRole = session()->pull('auth_role', 'buyer');
        if ($requestedRole === 'seller') {
            if ($user->seller && $user->seller->isVerified()) {
                return redirect()->route('seller.dashboard');
            }

            return redirect()->route('seller.register')
                ->with('info', 'Silakan masukkan Kode Akses VIP Anda untuk mengaktifkan akun seller.');
        }

        return redirect()->intended('/');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Local development & testing fast login
     */
    public function devLogin(string $role = 'buyer'): RedirectResponse
    {
        if (app()->isProduction()) {
            abort(404);
        }

        $user = match ($role) {
            'admin' => User::where('email', 'admin@whimarket.com')->first(),
            'bintang' => User::where('email', 'bintang.creator@gmail.com')->first(),
            'seller' => User::where('email', 'celloszx@whimarket.com')->first()
                ?? User::where('role', UserRole::SELLER)->first(),
            'fresh' => User::where('email', 'rina.melati@example.com')->first(),
            default => User::where('email', 'buyer@whimarket.com')->first()
                ?? User::where('role', UserRole::BUYER)->first(),
        };

        if ($user) {
            Auth::login($user, true);
            request()->session()->regenerate();
            if (! $user->addresses()->exists()) {
                request()->session()->flash('show_onboarding_modal', true);
            }

            if ($user->isSeller() && $user->seller) {
                return redirect()->route('seller.dashboard');
            }

            return redirect()->to('/');
        }

        return redirect()->back();
    }
}
