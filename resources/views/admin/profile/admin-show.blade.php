@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Pengguna</h3>
                <p class="text-subtitle text-muted">
                    Halaman Detail Pengguna
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Pengguna
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded">
        <div class="card-body position-relative">
            <div class="text-center mb-4" style="margin-top: -50px;">
                <div class="profile-picture-frame">
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" class="profile-picture">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Lengkap:</strong> <span class="text-muted">{{ $user->name ?? '-' }}</span></p>
                    <p><strong>Email:</strong> <span class="text-muted">{{ $user->email ?? '-' }}</span></p>
                    <p><strong>Tempat Lahir:</strong> <span class="text-muted">{{ $user->place_birth ?? '-' }}</span>
                    </p>
                    <p><strong>Tanggal Lahir:</strong> <span class="text-muted">{{ $user->date_birth ?
                            \Carbon\Carbon::parse($user->date_birth)->format('d-m-Y') : '-' }}</span></p>
                    <p><strong>Alamat:</strong> <span class="text-muted">{{ $user->address ?? '-' }}</span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Nomor Telepon:</strong> <span class="text-muted">{{ $user->phone_number ?? '-' }}</span>
                    </p>
                    <p><strong>Sekolah/Kuliah:</strong> <span class="text-muted">{{ $user->school ?? '-' }}</span></p>
                    <p><strong>Jurusan/Prodi:</strong> <span class="text-muted">{{ $user->major ?? '-' }}</span></p>
                    <p><strong>Mulai Magang:</strong> <span class="text-muted">{{ $user->internship_start ?
                            \Carbon\Carbon::parse($user->internship_start)->format('d-m-Y') : '-' }}</span></p>
                    <p><strong>Selesai Magang:</strong> <span class="text-muted">{{ $user->internship_end ?
                            \Carbon\Carbon::parse($user->internship_end)->format('d-m-Y') : '-' }}</span></p>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('admin.profile.edit', $user->id) }}" class="btn btn-warning float-end">Edit
                    Profile</a>
                <a href="{{ route('admin.profile.admin-user') }}" class="btn btn-secondary float-start">Kembali</a>
            </div>
        </div>
    </div>

    <!-- Tabel Kegiatan -->
    <div class="card-body">
        <div class="table-responsive" style="max-width: 100%; overflow-x: auto;">
            <table class="table table-bordered" id="activityTable">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Deskripsi</th>
                        <th class="text-center">Foto</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($activity->date)->format('d-m-Y') }}</td>
                        <td>{{ $activity->description }}</td>
                        <td class="text-center">
                            @if ($activity->photo)
                            <img src="{{ asset('storage/' . $activity->photo) }}" alt="Foto Kegiatan" width="100"
                                style="max-width: 100px; max-height: 100px; object-fit: cover; cursor: pointer;"
                                data-bs-toggle="modal" data-bs-target="#imageModal"
                                onclick="showImage('{{ asset('storage/' . $activity->photo) }}')">
                            @else
                            Tidak ada foto
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex justify-content-center align-items-center">
                                <a href="{{ route('activities.edit', $activity->id) }}"
                                    class="btn btn-warning btn-sm d-flex align-items-center mx-1"
                                    style="line-height: 1;">
                                    <i class="bi bi-pencil-square" style="font-size: 16px; vertical-align: middle;"></i>
                                    <span class="ms-1">Edit</span>
                                </a>

                                {{-- Show Kegiatan --}}
                                <a href="{{ route('activities.show', $activity->id) }}"
                                    class="btn btn-info btn-sm d-flex align-items-center mx-1" style="line-height: 1;">
                                    <i class="bi bi-eye" style="font-size: 16px; vertical-align: middle;"></i>
                                    <span class="ms-1">Lihat</span>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm d-flex align-items-center mx-1"
                                    onclick="confirmDelete({{ $activity->id }})" style="line-height: 1;">
                                    <i class="bi bi-trash" style="font-size: 16px; vertical-align: middle;"></i>
                                    <span class="ms-1">Hapus</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada kegiatan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal Preview Gambar -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview Foto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="previewImage" src="" alt="Preview Foto"
                            style="width: 100%; max-height: 500px; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data ini?</p>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" action="" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

<script src="{{ asset('assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
<script>
    // Fungsi untuk modal preview foto
    function showImage(src) {
        document.getElementById("previewImage").src = src;
    }

    // Fungsi modal konfirmasi hapus
    function confirmDelete(activityId) {
        var formAction = "/activities/" + activityId;
        document.getElementById("deleteForm").action = formAction;
        var deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        deleteModal.show();
    }

    // Optional: auto-hide alert sukses
    window.onload = function () {
        const successAlert = document.getElementById("success-alert");
        if (successAlert) {
            setTimeout(() => successAlert.style.display = 'none', 3000);
        }
    };

    // Optional: inisialisasi datatables (kalau pakai simple-datatables)
    document.addEventListener("DOMContentLoaded", function () {
        const dataTable = new simpleDatatables.DataTable("#activityTable", {
            sortable: false
        });
    });
</script>
