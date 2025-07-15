<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TemplatePenilaianController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSuratMasukController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Halaman utama
Route::get('/', function () {
    return view('home', [
        'title' => 'Home',
    ]);
});
// Halaman Tentang
Route::get('/tentang', function () {
    return view('about', [
        'title' => 'Tentang'
    ]);
});
// Halaman Kontak
Route::get('/kontak', function () {
    return view('contact', [
        'title' => 'Kontak'
    ]);
});

// Route Login dan Register
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Grup route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    Route::get('/user/dashboard', function () {
        return view('dashboard.index');
    })->name('user.dashboard');

    // Menu Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Aktivitas
    Route::resource('activities', ActivityController::class);

    // Kanban Board
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');

    // Surat Masuk
    Route::resource('surat-masuk', SuratMasukController::class);

    // Template Penilaian
    Route::resource('template-penilaian', TemplatePenilaianController::class);
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Profile Admin
    Route::get('profile', [AdminController::class, 'index'])->name('profile.admin-user');
    Route::get('profile/create', [AdminController::class, 'create'])->name('profile.create');
    Route::post('profile', [AdminController::class, 'store'])->name('profile.store');
    Route::get('profile/{user}', [AdminController::class, 'show'])->name('profile.show');
    Route::get('profile/{user}/edit', [AdminController::class, 'edit'])->name('profile.edit');
    Route::put('profile/{user}', [AdminController::class, 'admin_update'])->name('profile.admin_update');
    Route::delete('profile/{user}', [AdminController::class, 'destroy'])->name('profile.destroy');

    // Surat Masuk Admin
    Route::get('surat-masuk', [AdminSuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::put('surat-masuk/{id}/status', [AdminSuratMasukController::class, 'updateStatus'])->name('surat-masuk.update-status');

    // Kanban Board
    Route::get('profile/{user}/kanban', [AdminController::class, 'kanban'])->name('kanban.index');
});

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TemplatePenilaianController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSuratMasukController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Halaman utama
Route::get('/', function () {
    return view('home', [
        'title' => 'Home',
    ]);
});

// Halaman Tentang
Route::get('/tentang', function () {
    return view('about', [
        'title' => 'Tentang'
    ]);
});

// Halaman Kontak
Route::get('/kontak', function () {
    return view('contact', [
        'title' => 'Kontak'
    ]);
});

// Route Login dan Register
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Grup route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    Route::get('/user/dashboard', function () {
        return view('dashboard.index');
    })->name('user.dashboard');

    // Menu Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Aktivitas
    Route::resource('activities', ActivityController::class);

    // Kanban Board
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');

    // Surat Masuk
    Route::resource('surat-masuk', SuratMasukController::class);

    // Template Penilaian
    Route::resource('template-penilaian', TemplatePenilaianController::class);
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Profile Admin
    Route::get('profile', [AdminController::class, 'index'])->name('profile.admin-user');
    Route::get('profile/create', [AdminController::class, 'create'])->name('profile.create');
    Route::post('profile', [AdminController::class, 'store'])->name('profile.store');
    Route::get('profile/{user}', [AdminController::class, 'show'])->name('profile.show');
    Route::get('profile/{user}/edit', [AdminController::class, 'edit'])->name('profile.edit');
    Route::put('profile/{user}', [AdminController::class, 'admin_update'])->name('profile.admin_update');
    Route::delete('profile/{user}', [AdminController::class, 'destroy'])->name('profile.destroy');

    // Surat Masuk Admin
    Route::get('surat-masuk', [AdminSuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::put('surat-masuk/{id}/status', [AdminSuratMasukController::class, 'updateStatus'])->name('surat-masuk.update-status');

    // Kanban Board
    Route::get('profile/{user}/kanban', [AdminController::class, 'kanban'])->name('kanban.index');
});

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
