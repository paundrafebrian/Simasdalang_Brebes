<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $suratMasuk = SuratMasuk::all(); // Menampilkan semua surat masuk
        $suratMasuk = SuratMasuk::where('user_id', Auth::id())->get();

        return view('surat-masuk.index', compact('suratMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('surat-masuk.create'); // Menampilkan form tambah surat masuk
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required',
            'tanggal' => 'required|date',
            'asal_pengirim' => 'required',
            'file_pdf' => 'required|mimes:pdf|max:2048',
        ]);

        $filePath = $request->file('file_pdf')->store('pdfs', 'public');

        SuratMasuk::create([
            'no_surat' => $request->no_surat,
            'tanggal' => $request->tanggal,
            'asal_pengirim' => $request->asal_pengirim,
            'file_pdf' => $filePath,
            'user_id' => Auth::id(), // ✅ WAJIB ditambahkan agar tidak error
        ]);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(SuratMasuk $suratMasuk)
    {
        return view('surat-masuk.show', compact('suratMasuk')); // Menampilkan detail surat masuk
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratMasuk $suratMasuk)
    {
        return view('surat-masuk.edit', compact('suratMasuk')); // Menampilkan form edit surat masuk
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $validatedData = $request->validate([
            'no_surat' => 'required|string|unique:surat_masuks,no_surat,' . $suratMasuk->id, // Validasi untuk nomor surat dengan pengecualian surat yang sedang diedit
            'tanggal' => 'required|date',
            'asal_pengirim' => 'required|string|max:255',
            'file_pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Menyimpan file PDF jika ada
        if ($request->hasFile('file_pdf')) {
            // Menghapus file PDF lama jika ada
            if ($suratMasuk->file_pdf && Storage::disk('public')->exists($suratMasuk->file_pdf)) {
                Storage::disk('public')->delete($suratMasuk->file_pdf);
            }

            // Menyimpan file PDF yang baru
            $validatedData['file_pdf'] = $request->file('file_pdf')->store('pdfs', 'public');
        }

        // Memperbarui data surat masuk
        $suratMasuk->update($validatedData);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratMasuk $suratMasuk)
    {
        // Menghapus file PDF jika ada
        if ($suratMasuk->file_pdf && Storage::disk('public')->exists($suratMasuk->file_pdf)) {
            Storage::disk('public')->delete($suratMasuk->file_pdf);
        }

        // Menghapus data surat masuk dari database
        $suratMasuk->delete();

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }
}
