<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['required'],
        ], [
            'email.unique'            => 'Email sudah digunakan oleh akun lain.',
            'current_password.required' => 'Password wajib diisi untuk konfirmasi.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password yang kamu masukkan salah.'])->withInput();
        }

        $user->update(['email' => $request->email]);

        return back()->with('success', 'Email berhasil diperbarui menjadi ' . $request->email);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ], [
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini yang kamu masukkan salah.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui. Gunakan password baru untuk login berikutnya.');
    }
}
