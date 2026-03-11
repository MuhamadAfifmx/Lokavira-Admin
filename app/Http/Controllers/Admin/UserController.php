<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->with('package')->latest()->get();
        $packages = Package::all();
        return view('admin.users.index', compact('users', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|unique:users',
            'password' => 'required|min:6',
            'package_id' => 'required',
            'representative_name' => 'required',
        ]);

        $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('logos', 'public') : null;

        User::create([
            'business_name'       => $request->business_name,
            'phone_number'        => $request->phone_number,
            'username'            => $request->phone_number, // Phone sebagai username login
            'email'               => $request->email,
            'password'            => Hash::make($request->password),
            'representative_name' => $request->representative_name,
            'address'             => $request->address,
            'package_id'          => $request->package_id,
            'logo'                => $logoPath,
            'subscribed_at'       => now(),
            'expires_at'          => now()->addMonth(),
            'is_admin'            => false,
        ]);

        return back()->with('success', 'Data PIC berhasil ditambahkan!');
    }

  public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Generate password baru 6 digit ANGKA saja
        $newPassword = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // Format nomor ke standar internasional (62)
        $userPhone = preg_replace('/[^0-9]/', '', $user->phone_number);
        if (substr($userPhone, 0, 1) === '0') {
            $userPhone = '62' . substr($userPhone, 1);
        }

        $tokenFull = 'VRVARb0BQ34YSJt0wtE1D9AGgRGd1DQ4Dn0MzjVi6qXRMvjW8woOLqNiNOftJpxO.DBGAVT5K';
        $domain    = 'https://kudus.wablas.com';

        // Format pesan sesuai contoh abang
        $message = "LokaVira:\n"; // Tambah awalan LokaVira
        $message .= "Halo *{$user->representative_name}*,\n\n";
        $message .= "Password login Anda untuk unit *{$user->business_name}* telah direset oleh Admin Lokavira.\n\n";
        $message .= "Username: *{$user->phone_number}*\n";
        $message .= "Password Baru: *{$newPassword}*\n\n";
        $message .= "Silakan login kembali dan segera ganti password Anda demi keamanan.";

        try {
            Http::withHeaders(['Authorization' => $tokenFull])
                ->withOptions(['verify' => false])->asForm()
                ->post($domain . '/api/send-message', [
                    'phone'   => $userPhone,
                    'message' => $message,
                ]);

            return back()->with('success', 'Password berhasil direset ('.$newPassword.') dan dikirim ke WhatsApp!');
        } catch (\Exception $e) {
            return back()->with('error', 'Password direset di sistem, tapi gagal kirim WA: ' . $e->getMessage());
        }
    }

 public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$id,
            'phone_number' => 'required|unique:users,phone_number,'.$id,
            'business_name' => 'required',
            'representative_name' => 'required',
            'expires_at' => 'required|date', // Tambah validasi tanggal
        ]);

        $data = $request->only([
            'business_name', 
            'phone_number', 
            'email', 
            'representative_name', 
            'address', 
            'package_id',
            'expires_at' // Masukkan data expired ke update
        ]);

        $data['username'] = $request->phone_number; 

        if ($request->hasFile('logo')) {
            if ($user->logo) Storage::disk('public')->delete($user->logo);
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Data Mitra & Masa Aktif berhasil diperbarui!');
    }
}