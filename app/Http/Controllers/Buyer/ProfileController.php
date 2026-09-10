<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show account settings and address management.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->latest('id')
            ->get();

        return view('profile.settings', [
            'title' => 'Pengaturan Akun & Alamat | WhiMarket',
            'user' => $user,
            'addresses' => $addresses,
            'activeTab' => $request->query('tab', 'biodata'),
        ]);
    }

    /**
     * Update user profile information (name, phone, email, avatar).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:25'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'remove_avatar' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'avatar.image' => 'Foto profil harus berupa file gambar.',
            'avatar.max' => 'Ukuran foto profil maksimal 5MB.',
        ]);

        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && str_starts_with($user->avatar, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }
            $user->avatar = null;
        } elseif ($request->hasFile('avatar')) {
            if ($user->avatar && str_starts_with($user->avatar, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }
            $avatarFile = $request->file('avatar');
            $avatarName = 'avatar_'.$user->id.'_'.Str::uuid().'.'.$avatarFile->getClientOriginalExtension();
            $avatarPath = '/storage/'.$avatarFile->storeAs('avatars', $avatarName, 'public');
            $user->avatar = $avatarPath;
        }

        $rawPhone = $validated['phone'] ?? null;
        $normalizedPhone = null;

        if (! empty($rawPhone)) {
            $digits = preg_replace('/[^0-9]/', '', $rawPhone);
            if (str_starts_with($digits, '62')) {
                $normalizedPhone = '+'.$digits;
            } elseif (str_starts_with($digits, '0')) {
                $normalizedPhone = '+62'.substr($digits, 1);
            } elseif ($digits !== '') {
                $normalizedPhone = '+62'.$digits;
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $normalizedPhone;
        $user->save();

        return redirect('/akun/pengaturan?tab=biodata')->with('success', 'Profil akun Anda berhasil diperbarui!');
    }

    /**
     * Store a new delivery address.
     */
    public function storeAddress(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:25'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:15'],
            'full_address' => ['required', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ], [
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor handphone wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'city.required' => 'Kota/Kabupaten wajib diisi.',
            'full_address.required' => 'Alamat lengkap wajib diisi.',
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

        $isFirstAddress = $user->addresses()->count() === 0;
        $shouldBeDefault = $request->boolean('is_default') || $isFirstAddress;

        if ($shouldBeDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'province' => $validated['province'],
            'city' => $validated['city'],
            'district' => $validated['district'] ?? '',
            'postal_code' => $validated['postal_code'] ?? '',
            'full_address' => $validated['full_address'],
            'is_default' => $shouldBeDefault,
        ]);

        return redirect('/akun/pengaturan?tab=alamat')->with('success', 'Alamat pengiriman baru berhasil ditambahkan!');
    }

    /**
     * Update an existing address.
     */
    public function updateAddress(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:25'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:15'],
            'full_address' => ['required', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ], [
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor handphone wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'city.required' => 'Kota/Kabupaten wajib diisi.',
            'full_address.required' => 'Alamat lengkap wajib diisi.',
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

        if ($request->boolean('is_default')) {
            $user->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
            $address->is_default = true;
        }

        $address->update([
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'province' => $validated['province'],
            'city' => $validated['city'],
            'district' => $validated['district'] ?? '',
            'postal_code' => $validated['postal_code'] ?? '',
            'full_address' => $validated['full_address'],
            'is_default' => $request->boolean('is_default') ? true : $address->is_default,
        ]);

        return redirect('/akun/pengaturan?tab=alamat')->with('success', 'Alamat pengiriman berhasil diperbarui!');
    }

    /**
     * Delete an address.
     */
    public function destroyAddress(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);
        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault) {
            $nextAddress = $user->addresses()->latest('id')->first();
            if ($nextAddress) {
                $nextAddress->update(['is_default' => true]);
            }
        }

        return redirect('/akun/pengaturan?tab=alamat')->with('success', 'Alamat pengiriman berhasil dihapus.');
    }

    /**
     * Set an address as primary default.
     */
    public function setDefaultAddress(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect('/akun/pengaturan?tab=alamat')->with('success', 'Alamat utama berhasil diubah!');
    }
}
