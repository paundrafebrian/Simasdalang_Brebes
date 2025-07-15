@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>
                    Dashboard {{ Auth::user()->role == 'admin' ? 'Admin' : 'Instansi' }}
                </h3>
                <p class="text-subtitle text-muted">
                    Halaman utama untuk {{ Auth::user()->role == 'admin' ? 'admin' : 'instansi' }}
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/admin/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ Auth::user()->role == 'admin' ? 'Admin Dashboard' : 'Instansi Dashboard' }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Dashboard Content -->
    <section class="row">
        <div class="col-12">
            <div class="row">
                <!-- Panel Pengguna (bisa juga dibatasi kalau mau) -->
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pengguna</h4>
                        </div>
                        <div class="card-body">
                            <p>Kelola pengguna yang terdaftar</p>
                            <a href="{{ route('admin.profile.admin-user') }}" class="btn btn-primary">Lihat Pengguna</a>
                        </div>
                    </div>
                </div>

                <!-- Hanya tampil jika role-nya admin -->
                @if(Auth::user()->role == 'admin')
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Surat Masuk</h4>
                        </div>
                        <div class="card-body">
                            <p>Kelola surat masuk yang diterima oleh sistem</p>
                            <a href="{{ route('admin.surat-masuk.index') }}" class="btn btn-primary">Lihat Surat Masuk</a>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>
</div>
@endsection
