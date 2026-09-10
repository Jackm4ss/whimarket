<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function saveAddress(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'full_address' => 'required|string|max:1000',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);

        if (! empty($validated['phone'])) {
            $digits = preg_replace('/[^0-9]/', '', $validated['phone']);
            if (str_starts_with($digits, '62')) {
                $validated['phone'] = '+62'.substr($digits, 2);
            } elseif (str_starts_with($digits, '0')) {
                $validated['phone'] = '+62'.substr($digits, 1);
            } elseif ($digits !== '') {
                $validated['phone'] = '+62'.$digits;
            }
        }

        $user = Auth::user();
        // Update phone if empty
        if (! $user->phone) {
            $user->update(['phone' => $validated['phone']]);
        }

        // Set previous addresses to non-default
        $user->addresses()->update(['is_default' => false]);
        $validated['district'] = ! empty($validated['district']) ? $validated['district'] : ($validated['city'] ?? '');
        $validated['postal_code'] = ! empty($validated['postal_code']) ? $validated['postal_code'] : '';

        $address = $user->addresses()->create(array_merge($validated, [
            'is_default' => true,
        ]));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat utama berhasil disimpan!',
                'address' => $address,
            ]);
        }

        return redirect()->back()->with('success', 'Alamat utama berhasil disimpan!');
    }

    public function skip(): JsonResponse|RedirectResponse
    {
        session()->forget('show_onboarding_modal');

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
