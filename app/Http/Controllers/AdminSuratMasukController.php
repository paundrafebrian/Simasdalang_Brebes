<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;

class AdminSuratMasukController extends Controller
{
    public function index()
    {
        // Ambil semua data Surat Masuk dari database
        $suratMasuk = SuratMasuk::all();

        // Kirim data ke tampilan admin
        return view('admin.surat-masuk.index', compact('suratMasuk'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Ditolak,Diterima'
        ]);

        $surat = SuratMasuk::findOrFail($id);
        $surat->status = $request->status;
        $surat->save();

        return redirect()->route('admin.surat-masuk.index')->with('success', 'Status surat berhasil diperbarui.');
    }

}
