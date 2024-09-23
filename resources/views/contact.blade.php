@extends('layouts.main')

@section('content')
  <!-- Contact Section -->
  <section id="contact" class="contact section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>Kontak</h2>
      <p>Hubungi kami jika ada hal perlu ditanyakan, melalui form berikut:</p>
    </div><!-- End Section Title -->

    <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4">
        <div class="col-lg-5">

          <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
            <i class="bi bi-geo-alt flex-shrink-0"></i>
            <div>
              <h3>Alamat</h3>
              <p>Jl. MT Haryono Jl. Saditan Baru No.76, RW.01, Saditan, Brebes, Kec. Brebes, Kabupaten Brebes, Jawa Tengah 52212</p>
            </div>
          </div><!-- End Info Item -->

          <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
            <i class="bi bi-telephone flex-shrink-0"></i>
            <div>
              <h3>Telepon</h3>
              <p>+62 877-3879-1100</p>
            </div>
          </div><!-- End Info Item -->

          <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
            <i class="bi bi-envelope flex-shrink-0"></i>
            <div>
              <h3>Email</h3>
              <p>dinkominfotik@brebeskab.go.id</p>
              <p>madinkominfotik@gmail.com</p>
            </div>
          </div><!-- End Info Item -->

        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
          <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
            data-aos-delay="500">
            <div class="row gy-4">
              <div class="col-md-6">
                <input type="text" name="name" class="form-control" placeholder="Nama Anda" required="">
              </div>
              <div class="col-md-6 ">
                <input type="email" class="form-control" name="email" placeholder="Email" required="">
              </div>
              <div class="col-md-12">
                <input type="text" class="form-control" name="subject" placeholder="Subjek" required="">
              </div>
              <div class="col-md-12">
                <textarea class="form-control" name="message" rows="6" placeholder="Pesan" required=""></textarea>
              </div>
              <div class="col-md-12 text-center">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>

                <button type="submit">Kirim Pesan</button>
              </div>
            </div>
          </form>
        </div><!-- End Contact Form -->
        
      </div>
    </div>

  </section><!-- /Contact Section -->
@endsection
