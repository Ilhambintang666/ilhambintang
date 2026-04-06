<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
    public function showPeminjamForm()
{
    return view('auth.register-peminjam'); // file blade yang kamu buat
}

    public function registerPeminjam(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peminjam',
        ]);

        return redirect()->route('login')->with('success', 'Berhasil daftar! Silakan login untuk mengajukan peminjaman.');
    }
}
