<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemplatePenilaian;
use Illuminate\Support\Facades\Storage;

class TemplatePenilaianController extends Controller
{
    public function index()
    {
        $templates = TemplatePenilaian::latest()->get();
        return view('template-penilaian.index', compact('templates'));
    }

    public function create()
    {
        return view('template-penilaian.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_template' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('template-penilaian', 'public');
        }

        TemplatePenilaian::create($validated);

        return redirect()->route('template-penilaian.index')->with('success', 'Template berhasil ditambahkan.');
    }

    public function show(TemplatePenilaian $templatePenilaian)
    {
        return view('template-penilaian.show', compact('templatePenilaian'));
    }

    public function edit(TemplatePenilaian $templatePenilaian)
    {
        return view('template-penilaian.edit', compact('templatePenilaian'));
    }

    public function update(Request $request, TemplatePenilaian $templatePenilaian)
    {
        $validated = $request->validate([
            'nama_template' => 'required|string|max:255',
            'file' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($templatePenilaian->file && Storage::disk('public')->exists($templatePenilaian->file)) {
                Storage::disk('public')->delete($templatePenilaian->file);
            }

            $validated['file'] = $request->file('file')->store('template-penilaian', 'public');
        }

        $templatePenilaian->update($validated);

        return redirect()->route('template-penilaian.index')->with('success', 'Template berhasil diperbarui.');
    }

    public function destroy(TemplatePenilaian $templatePenilaian)
    {
        if ($templatePenilaian->file && Storage::disk('public')->exists($templatePenilaian->file)) {
            Storage::disk('public')->delete($templatePenilaian->file);
        }

        $templatePenilaian->delete();

        return redirect()->route('template-penilaian.index')->with('success', 'Template berhasil dihapus.');
    }
}
