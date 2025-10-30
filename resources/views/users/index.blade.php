@extends('layouts.app')

@section('content')
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Manajemen User
        </h1>
    </div>
</header>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <livewire:user-management />
    </div>
</div>

{{-- Modal Dummy: Edit Permission --}}
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-labelledby="editPermissionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="editPermissionLabel">Edit Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Fitur ini belum terhubung ke backend. Tampilan ini hanya simulasi.</p>

                <div class="mb-3">
                    <label class="form-label">Nama User</label>
                    <input type="text" id="permissionUserName" class="form-control" readonly>
                </div>

                <label class="form-label">Daftar Permission</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="perm1" checked disabled>
                    <label class="form-check-label" for="perm1">Lihat Dashboard</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="perm2" disabled>
                    <label class="form-check-label" for="perm2">Edit Data</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="perm3" disabled>
                    <label class="form-check-label" for="perm3">Hapus Data</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" disabled>Simpan (Coming Soon)</button>
            </div>
        </div>
    </div>
</div>

{{-- Script agar nama user tampil di modal --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editPermissionModal');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userName = button.getAttribute('data-user');
            const input = modal.querySelector('#permissionUserName');
            input.value = userName;
        });
    });
</script>
@endsection
