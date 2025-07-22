@extends('layouts.app')

@section('content')

<style>
    #kanban-columns {
        scroll-behavior: smooth;
    }

    .kanban-card {
        background-color: #1e1e1e;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 10px;
        margin-top: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        color: #fff;
        cursor: grab;
    }

    .kanban-card:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    #comments-container>div {
        background-color: #f8f9fa;
        padding: 8px 12px;
        margin-bottom: 6px;
        border-radius: 6px;
        font-size: 14px;
    }

    #comments-container strong {
        color: #333;
    }

    #comments-container small {
        color: #999;
    }
</style>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Kanban Board {{ $activity->name }}</h3>
        {{-- <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addColumnModal">+ Kolom</button>
        --}}
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'instansi')
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addColumnModal">+ Kolom</button>
        @endif

    </div>

    <div id="kanban-columns" class="d-flex flex-row gap-3" style="overflow-x: auto; padding-bottom: 1rem;">
        @foreach($activity->kanbanColumns as $column)
        <div style="min-width: 250px;">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <strong>{{ $column->name }}</strong>
                    <button class="btn btn-sm btn-light" onclick="openAddCardModal({{ $column->id }})">+</button>
                </div>
                {{-- BENAR: loop foreach di luar, dan onclick dalam masing-masing card --}}
                <div class="card-body kanban-column" data-column-id="{{ $column->id }}" ondrop="drop(event)"
                    ondragover="allowDrop(event)">
                    @foreach($column->kanbanCards as $card)
                    <div class="kanban-card draggable-card" draggable="true" ondragstart="drag(event)"
                        data-card-id="{{ $card->id }}" onclick="openCardNote({{ $card->id }})">
                        <p class="mb-1 fw-semibold">{{ $card->title }}</p>
                        <div class="d-flex align-items-center text-muted" style="font-size: 12px;">
                            <i class="bi bi-card-text me-1"></i>
                            <span>{{ $card->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Modal Tambah Kolom --}}
<div class="modal fade" id="addColumnModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="add-column-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kolom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="name" class="form-control" placeholder="Nama kolom" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Tambah</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tambah Card --}}
<div class="modal fade" id="addCardModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="add-card-form">
            @csrf
            <input type="hidden" name="column_id" id="card-column-id">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="title" class="form-control" placeholder="Judul Card" required>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Tambah</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Detail Catatan --}}
<div class="modal fade" id="cardNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="note-form">
            @csrf
            <input type="hidden" id="note-card-id">
            <div class="modal-header">
                <h5 class="modal-title">Catatan Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- Catatan --}}
                <label for="card-note" class="form-label">Catatan</label>
                <textarea id="card-note" class="form-control mb-3" rows="3" placeholder="Tulis catatan..."></textarea>
                <button type="submit" class="btn btn-primary mb-4">Simpan Catatan</button>

                {{-- Komentar --}}
                <hr>
                <h6 class="fw-semibold">Komentar</h6>
                <div id="comments-container" class="mb-3" style="max-height: 200px; overflow-y:auto;"></div>

                {{-- <textarea id="new-comment" class="form-control mb-2" rows="2"
                    placeholder="Tulis komentar (@username)"></textarea> --}}

                <div class="position-relative">
                    <textarea id="new-comment" class="form-control mt-2" rows="2"
                        placeholder="Tulis komentar (gunakan @username untuk mention)"></textarea>
                    <ul id="mention-list" class="list-group position-absolute w-100 d-none" style="z-index: 999;"></ul>
                </div>
                <button type="button" class="btn btn-sm btn-secondary mb-4" onclick="submitComment()">Kirim</button>
            </div>
        </form>
    </div>
</div>


@endsection

@section('scripts')
{{-- SweetAlert CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("cardId", ev.target.dataset.cardId);
    }

    function drop(ev) {
        ev.preventDefault();
        let cardId = ev.dataTransfer.getData("cardId");
        let columnId = ev.currentTarget.dataset.columnId;

        fetch(`{{ route('kanban.cards.move', ':id') }}`.replace(':id', cardId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ column_id: columnId, order: 1 })
        }).then(() => location.reload());
    }

    document.addEventListener('DOMContentLoaded', function () {
        const columnForm = document.getElementById('add-column-form');
        columnForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = columnForm.querySelector('[name=name]').value;

            fetch(`{{ route('kanban.columns.store', $activity->id) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name })
            })
            .then(res => {
                if (!res.ok) throw new Error("Gagal menambahkan kolom");
                return res.json();
            })
            .then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addColumnModal'));
                modal.hide();
                columnForm.reset();
                Swal.fire('Berhasil!', 'Kolom berhasil ditambahkan.', 'success');
                setTimeout(() => location.reload(), 1000);
            })
            .catch(() => {
                Swal.fire('Gagal!', 'Gagal menambahkan kolom.', 'error');
            });
        });

        const cardForm = document.getElementById('add-card-form');
        cardForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const columnId = cardForm.querySelector('[name=column_id]').value;
            const title = cardForm.querySelector('[name=title]').value;

            fetch(`{{ route('kanban.cards.store', ':column') }}`.replace(':column', columnId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ title })
            })
            .then(res => {
                if (!res.ok) throw new Error("Gagal menambahkan card");
                return res.json();
            })
            .then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addCardModal'));
                modal.hide();
                cardForm.reset();
                Swal.fire('Berhasil!', 'Card berhasil ditambahkan.', 'success');
                setTimeout(() => location.reload(), 1000);
            })
            .catch(() => {
                Swal.fire('Gagal!', 'Gagal menambahkan card.', 'error');
            });
        });
    });

    function openAddCardModal(columnId) {
        document.getElementById('card-column-id').value = columnId;
        const modal = new bootstrap.Modal(document.getElementById('addCardModal'));
        modal.show();
    }

    // Modal: buka isi notes
    function openCardNote(cardId) {
        fetch(`/kanban/cards/${cardId}`)
            .then(res => res.json())
            .then(data => {
                const card = data.card;
                document.getElementById('note-card-id').value = card.id;
                document.getElementById('card-note').value = card.notes ?? '';

                const commentsContainer = document.getElementById('comments-container');
                commentsContainer.innerHTML = '';
                card.comments.forEach(comment => {
                    commentsContainer.innerHTML += `
                        <div class="border-bottom py-1">
                            <strong>${comment.user.name}</strong><br>
                            ${comment.comment}
                        </div>`;
                });

                const modal = new bootstrap.Modal(document.getElementById('cardNoteModal'));
                modal.show();
        });
    }

    function submitComment() {
    const cardId = document.getElementById('note-card-id').value;
    const comment = document.getElementById('new-comment').value;

    fetch(`/kanban/cards/${cardId}/comments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ comment })
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal menyimpan komentar');
        return res.json();
    })
    .then(data => {
        document.getElementById('new-comment').value = '';
        openCardNote(cardId);
        })
        .catch(err => {
            console.error('Komentar gagal:', err);
            alert('Terjadi kesalahan saat menyimpan komentar. Silakan cek log.');
        });
    }



    // Simpan notes saat submit
    document.getElementById('note-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const cardId = document.getElementById('note-card-id').value;
        const notes = document.getElementById('card-note').value;

        fetch(`/kanban/cards/${cardId}/update-notes`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ notes })
        })
        .then(() => {
            Swal.fire('Berhasil!', 'Catatan berhasil diperbarui.', 'success');
            bootstrap.Modal.getInstance(document.getElementById('cardNoteModal')).hide();
            setTimeout(() => location.reload(), 500);
        });
    });

</script>
<script>
    let mentionList = document.getElementById('mention-list');
    let commentBox = document.getElementById('new-comment');

    commentBox.addEventListener('input', function () {
        let value = this.value;
        let cursorPos = this.selectionStart;
        let lastAt = value.lastIndexOf('@', cursorPos - 1);

        if (lastAt !== -1) {
            let word = value.slice(lastAt + 1, cursorPos);
            if (word.length > 0) {
                fetch(`/kanban/users/autocomplete?query=${word}`)
                    .then(res => res.json())
                    .then(data => {
                        mentionList.innerHTML = '';
                        data.forEach(user => {
                            let li = document.createElement('li');
                            li.className = 'list-group-item list-group-item-action';
                            li.textContent = user.name;
                            li.onclick = function () {
                                let before = value.slice(0, lastAt);
                                let after = value.slice(cursorPos);
                                commentBox.value = `${before}@${user.name}, ${after}`;
                                mentionList.classList.add('d-none');
                                commentBox.focus();
                            };
                            mentionList.appendChild(li);
                        });
                        mentionList.classList.remove('d-none');
                    });
            } else {
                mentionList.classList.add('d-none');
            }
        } else {
            mentionList.classList.add('d-none');
        }
    });

    document.addEventListener('click', function (e) {
        if (!mentionList.contains(e.target) && e.target !== commentBox) {
            mentionList.classList.add('d-none');
        }
    });

    window._openCardNoteActual = function(cardId) {
        fetch(`/kanban/cards/${cardId}`)
            .then(res => res.json())
            .then(data => {
                const card = data.card;
                document.getElementById('note-card-id').value = card.id;
                document.getElementById('card-note').value = card.notes ?? '';

                const commentsContainer = document.getElementById('comments-container');
                commentsContainer.innerHTML = '';
                card.comments.forEach(comment => {
                    commentsContainer.innerHTML += `
                        <div class="border-bottom py-1">
                            <strong>${comment.user.name}</strong><br>
                            ${comment.comment}
                        </div>`;
                });

                const modal = new bootstrap.Modal(document.getElementById('cardNoteModal'));
                modal.show();
            });
    };

    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const cardId = urlParams.get('openCardId');

        if (cardId) {
            window._openCardNoteActual(cardId);
        }
    });
</script>

@endsection