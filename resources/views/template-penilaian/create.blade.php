@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Tambah Template Penilaian</h1>

    <form action="{{ route('template-penilaian.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama-template" class="form-label">Nama Template</label>
            <input type="text" class="form-control" id="nama_template" name="nama_template" required>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Upload PDF</label>
            <input type="file" class="form-control" id="file" name="file" accept="application/pdf">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection