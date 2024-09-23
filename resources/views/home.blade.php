@extends('layouts.main')

@section('content')
  <!-- Hero Section -->
  <section id="hero" class="hero section">

    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
          <h1>Sistem Informasi Data PKL dan Magang</h1>
          <p>Mudahnya kelola data peserta PKL dan Magang bersama Simasdalang</p>
          <div class="d-flex">
            <a href="#about" class="btn-get-started">Sign up</a>
            <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center"></a>
          </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img">
          <img src="assets/img/hero-img.png" class="img-fluid animated" alt="">
        </div>
      </div>
    </div>

  </section><!-- /Hero Section -->  
@endsection