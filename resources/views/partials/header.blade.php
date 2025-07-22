<header>
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="dropdown ms-auto">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">
                                    {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                                </h6>
                                <p class="mb-0 text-sm text-gray-600">
                                    {{ Auth::check() ? ucfirst(Auth::user()->role) : 'Guest' }}
                                </p>
                            </div>

                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ asset(Auth::user()->photo ? 'storage/' . Auth::user()->photo : 'assets/compiled/jpg/profile.jpg') }}"
                                        class="profile-picture" alt="User Photo" />
                                </div>
                            </div>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                        style="min-width: 11rem">
                        <li>
                            <h6 class="dropdown-header">Halo, {{ Auth::check() ? Auth::user()->name : 'Guest' }}</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>

                        @auth
                        <li>
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="icon-mid bi bi-box-arrow-left me-2"></i> Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                        @else
                        <li>
                            <a class="dropdown-item" href="{{ route('login') }}">
                                <i class="icon-mid bi bi-box-arrow-in-right me-2"></i> Login
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>

                <!-- Notifikasi Dropdown -->
                <div class="dropdown ms-auto">
                    <a href="#" class="dropdown-toggle position-relative" id="notificationDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell fs-4"></i>
                        <span id="notification-count"
                            class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                            0
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown"
                        style="min-width: 18rem; max-height: 400px; overflow-y: auto;">
                        <li>
                            <h6 class="dropdown-header">Notifikasi</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>

                        <!-- Daftar Notifikasi (dynamic) -->
                        <li id="notifications-list" style="max-height: 300px; overflow-y: auto;">
                            <div class="text-center text-muted py-2">Memuat notifikasi...</div>
                        </li>

                        <li>
                            <hr class="dropdown-divider" />
                        </li>

                        <li>
                            <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Lihat
                                Semua</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- Pastikan meta csrf token ada --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/notifications/list')
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('notifications-list');
                    const count = document.getElementById('notification-count');

                    count.textContent = data.count ?? 0;

                    if (!data.notifications || data.notifications.length === 0) {
                        list.innerHTML = '<div class="dropdown-item text-center text-muted">Tidak ada notifikasi</div>';
                        return;
                    }

                    list.innerHTML = ''; // Kosongkan dulu

                    data.notifications.forEach(notif => {
                        console.log('Notif:', notif); // debug
                        const li = document.createElement('li');
                        li.classList.add('dropdown-item');
                        li.innerHTML = `
                            <a href="#" class="d-block text-truncate" title="${notif.comment}" data-id="${notif.id}" data-card-id="${notif.card_id}">
                                <strong>${notif.commenter}</strong> menyebut kamu:<br/>
                                <small class="text-muted">${notif.comment}</small><br/>
                                <small class="text-muted">${notif.time}</small>
                            </a>
                        `;
                        list.appendChild(li);

                        const anchor = li.querySelector('a');
                        if(anchor) {
                            anchor.addEventListener('click', function(e) {
                                e.preventDefault();
                                const notifId = this.getAttribute('data-id');
                                const cardId = this.getAttribute('data-card-id');

                                if (!notifId || !cardId) {
                                    console.error('notifId atau cardId tidak ditemukan', {notifId, cardId});
                                    return;
                                }

                                fetch(`/notifications/${notifId}/read`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(result => {
                                    if (result.success) {
                                        let currentCount = parseInt(count.textContent);
                                        if (currentCount > 0) count.textContent = currentCount - 1;
                                        openCardNote(cardId);
                                    }
                                })
                                .catch(err => {
                                    console.error('Gagal menandai notifikasi:', err);
                                    openCardNote(cardId);
                                });
                            });
                        } else {
                            console.warn('Anchor tidak ditemukan di li:', li);
                        }
                    });
                })
                .catch(err => {
                    const list = document.getElementById('notifications-list');
                    list.innerHTML = '<div class="dropdown-item text-center text-danger">Gagal memuat notifikasi</div>';
                    console.error(err);
                });
        });

        function openCardNote(cardId) {
        // Jika fungsi asli ada (hanya di halaman Kanban), panggil
        if (typeof window._openCardNoteActual === 'function') {
            window._openCardNoteActual(cardId);
        } else {
            // Jika di luar Kanban, redirect ke Kanban dan kirim ID via query
            window.location.href = `/kanban?openCardId=${cardId}`;
        }
    }
    </script>
</header>