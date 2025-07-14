@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard Instansi</h3>
                <p class="text-subtitle text-muted">
                    Halaman utama untuk Instansi
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/admin/dashboard">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Instansi Dashboard
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Dashboard Admin Content -->
    <section class="row">
        <div class="col-12">
            <div class="row">
                <!-- Admin's control panel -->
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

                <!-- <div class="col-12 col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Kegiatan</h4>
                            </div>
                            <div class="card-body">
                                <p>Kelola dan pantau kegiatan yang dilakukan oleh pengguna</p>
                                <a href="{{ route('activities.index') }}" class="btn btn-primary">Lihat Kegiatan</a>
                            </div>
                        </div>
                    </div> -->

                {{-- <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Surat Masuk</h4>
                        </div>
                        <div class="card-body">
                            <p>Kelola surat masuk yang diterima oleh sistem</p>
                            <a href="{{ route('surat-masuk.index') }}" class="btn btn-primary">Lihat Surat Masuk</a>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
</div>
@endsection
