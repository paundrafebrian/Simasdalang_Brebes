<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Simasdalang</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="background-color: #f0f8ff;">
                <div class="form-container">
                    <h2>Register</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        @error('email')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        <input type="password" name="password" placeholder="Password" required>
                        @error('password')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
                        @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                        {{-- <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="user" {{ old('role')=='user' ? 'selected' : '' }}>User</option>
                                <!-- Admin option can be displayed conditionally if needed -->
                                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        <input type="submit" value="Register">
                    </form>
                    <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                    <p>Kembali ke <a href="/">Home</a></p>
                </div>
            </div>
            <div class="col-md-6 info-container">
                <h2 style="font-weight: bold; text-shadow: 4px 4px 7px black;">Selamat Datang di Simasdalang</h2>
                <img src="{{ asset('assets/img/welcome-simasdalang-auth.png') }}" alt="Gambar Deskripsi"
                    class="img-fluid rounded" style="width: 100%; height: 97%; margin-top: 30px;">
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>