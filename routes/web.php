<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TemplatePenilaianController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSuratMasukController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('home', [
        'title' => 'Home',
    ]);
});

// Route::get('/kegiatan', function () {
//     return view('posts', [
//         'title' => 'Kegiatan'
//     ]);
// });

Route::get('/tentang', function () {
    return view('about', [
        'title' => 'Tentang'
    ]);
});

Route::get('/kontak', function () {
    return view('contact', [
        'title' => 'Kontak'
    ]);
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();

//     // Mengarahkan ke dashboard berdasarkan role
//     if ($request->user()->role == 'admin') {
//         return redirect()->route('admin.dashboard');
//     } else {
//         return redirect()->route('user.dashboard');
//     }
// })->middleware(['auth', 'signed'])->name('verification.verify');

// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
//     return back()->with('message', 'Email verifikasi telah dikirim ulang.');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::middleware(['auth'])->group(function () {

    // Dashboard untuk Admin
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    // Dashboard untuk User
    Route::get('/user/dashboard', function () {
        return view('dashboard.index');
    })->name('user.dashboard');

    // Dashboard untuk Instansi
    // Route::get('/instansi/dashboard', function () {
    //     return view('dashboard.instansi');
    // })->name('instansi.dashboard');


    // Menu Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware(['auth'])->group(function () {
        Route::resource('activities', ActivityController::class);
    });

    // Menu Surat Masuk
    Route::resource('surat-masuk', SuratMasukController::class);


    Route::resource('template-penilaian', TemplatePenilaianController::class);

});



Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Menampilkan daftar pengguna
    Route::get('profile', [AdminController::class, 'index'])->name('profile.admin-user');
    // Form tambah pengguna
    Route::get('profile/create', [AdminController::class, 'create'])->name('profile.create');
    // Simpan pengguna baru
    Route::post('profile', [AdminController::class, 'store'])->name('profile.store');
    // Detail informasi pengguna
    Route::get('profile/{user}', [AdminController::class, 'show'])->name('profile.show');
    // Form edit pengguna
    Route::get('profile/{user}/edit', [AdminController::class, 'edit'])->name('profile.edit');
    // Kanban pengguna
    Route::get('profile/{user}/show', [AdminController::class, 'show'])->name('profile.show');
    // Update data pengguna
    Route::put('profile/{user}', [AdminController::class, 'admin_update'])->name('profile.admin_update');
    // Hapus pengguna
    Route::delete('profile/{user}', [AdminController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/surat-masuk', [AdminSuratMasukController::class, 'index'])->name('admin.surat-masuk.index');
    Route::put('/admin/surat-masuk/{id}/status', [AdminSuratMasukController::class, 'updateStatus'])->name('surat-masuk.update-status');

});

// CRUD Template Penilaian untuk role instansi
// Route::middleware(['auth', 'checkRole:instansi'])->prefix('instansi')->name('instansi.')->group(function () {
//     Route::resource('template-penilaian', TemplatePenilaianController::class);
// });

// CRUD Template Penilaian untuk role admin
// Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::resource('template-penilaian', AdminTemplatePenilaianController::class);
// });



Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
