@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-3">
        <div class="col">
            <h3>Kanban Board</h3>
            <p class="text-muted">Manajemen Kegiatan</p>
        </div>
        {{-- <div class="col-auto">
            <a href="{{ route('activities.create') }}" class="btn btn-primary">Tambah Kegiatan</a>
        </div> --}}
    </div>

    <div class="row">
        <!-- Kolom To Do -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">To Do</h5>
                </div>
                <div class="card-body" id="todo" ondrop="drop(event)" ondragover="allowDrop(event)">
                    @foreach($activities as $activity)
                    @if($activity->status == 'todo')
                    <div class="kanban-item card mb-2" id="item-{{ $activity->id }}" draggable="true"
                        ondragstart="drag(event)">
                        <div class="card-body">
                            <h6 class="card-title">{{ $activity->description }}</h6>
                            <p class="card-text">{{ $activity->date->format('d-m-Y') }}</p>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                onclick="editActivity({{ $activity->id }})">Edit</button>
                            <button class="btn btn-danger btn-sm"
                                onclick="deleteActivity({{ $activity->id }})">Hapus</button>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kolom In Progress -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">In Progress</h5>
                </div>
                <div class="card-body" id="in-progress" ondrop="drop(event)" ondragover="allowDrop(event)">
                    @foreach($activities as $activity)
                    @if($activity->status == 'in-progress')
                    <div class="kanban-item card mb-2" id="item-{{ $activity->id }}" draggable="true"
                        ondragstart="drag(event)">
                        <div class="card-body">
                            <h6 class="card-title">{{ $activity->description }}</h6>
                            <p class="card-text">{{ $activity->date->format('d-m-Y') }}</p>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                onclick="editActivity({{ $activity->id }})">Edit</button>
                            <button class="btn btn-danger btn-sm"
                                onclick="deleteActivity({{ $activity->id }})">Hapus</button>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kolom Done -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Done</h5>
                </div>
                <div class="card-body" id="done" ondrop="drop(event)" ondragover="allowDrop(event)">
                    @foreach($activities as $activity)
                    @if($activity->status == 'done')
                    <div class="kanban-item card mb-2" id="item-{{ $activity->id }}" draggable="true"
                        ondragstart="drag(event)">
                        <div class="card-body">
                            <h6 class="card-title">{{ $activity->description }}</h6>
                            <p class="card-text">{{ $activity->date->format('d-m-Y') }}</p>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                onclick="editActivity({{ $activity->id }})">Edit</button>
                            <button class="btn btn-danger btn-sm"
                                onclick="deleteActivity({{ $activity->id }})">Hapus</button>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Fungsi untuk memungkinkan drag
function allowDrop(ev) {
    ev.preventDefault();
}

// Fungsi untuk memulai drag
function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

// Fungsi untuk menangani drop
function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    var draggedElement = document.getElementById(data);
    ev.target.appendChild(draggedElement);

    // Kirim data ke server untuk memperbarui status
    let activityId = draggedElement.id.split('-')[1];
    let newStatus = ev.target.id;  // 'todo', 'in-progress', or 'done'
    updateActivityStatus(activityId, newStatus);
}

// Fungsi untuk mengupdate status aktivitas
function updateActivityStatus(activityId, newStatus) {
    fetch(`/activities/${activityId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Activity status updated:', data);
    })
    .catch(error => {
        console.error('Error updating activity status:', error);
    });
}

// Fungsi untuk menghapus aktivitas
function deleteActivity(activityId) {
    if (confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')) {
        fetch(`/activities/${activityId}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('item-' + activityId).remove();
        })
        .catch(error => {
            console.error('Error deleting activity:', error);
        });
    }
}
</script>
@endsection
