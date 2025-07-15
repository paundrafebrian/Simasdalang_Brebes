@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Template Penilaian</h3>
                <p class="text-subtitle text-muted">
                    Halaman Data Template Penilaian
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Template Penilaian
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'instansi')
    <a href="{{ route('template-penilaian.create') }}" class="btn btn-primary mb-3">Tambah Template Penilaian</a>
    @endif

    @if (session('success'))
    <div id="success-alert" class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Template</th>
                                <th class="text-center">File</th>
                                @if(Auth::user()->role === 'instansi')
                                <th class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $template)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $template->nama_template }}</td>
                                <td class="text-center">
                                    @if($template->file)
                                    <a href="{{ Storage::url($template->file) }}" target="_blank">Lihat File</a>
                                    @else
                                    Tidak ada file
                                    @endif
                                </td>
                                @if(Auth::user()->role === 'instansi')
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('template-penilaian.edit', $template->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>

                                        <form id="delete-form-{{ $template->id }}"
                                            action="{{ route('template-penilaian.destroy', $template->id) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $template->id }})">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Notifikasi hilang otomatis
    window.onload = function () {
        const successAlert = document.getElementById("success-alert");
        if (successAlert) {
            setTimeout(function () {
                successAlert.style.display = 'none';
            }, 3000);
        }
    };

    // SweetAlert Konfirmasi Hapus
    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection