@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Template Penilaian</h1>

    <form action="{{ route('template-penilaian.update', $templatePenilaian->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Nama Template -->
        <div class="mb-3">
            <label for="nama_template" class="form-label">Nama Template</label>
            <input type="text" class="form-control" id="nama_template" name="nama_template"
                value="{{ old('nama_template', $templatePenilaian->nama_template) }}" required>
        </div>

        <!-- File (Optional) -->
        <div class="mb-3">
            <label for="file" class="form-label">Upload File Baru (Opsional)</label>
            <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx">
            @if ($templatePenilaian->file)
            <p class="mt-2">File saat ini: <a href="{{ Storage::url($templatePenilaian->file) }}" target="_blank">Lihat
                    File</a></p>
            @endif
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-primary">Update Template</button>
        <a href="{{ route('template-penilaian.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection