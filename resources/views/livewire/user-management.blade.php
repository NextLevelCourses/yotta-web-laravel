<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar User</h4>
            <p class="card-title-desc">Kelola user dan ubah peran mereka (dummy).</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    <span class="badge {{ $user['role'] === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                        {{ ucfirst($user['role']) }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal" data-bs-target="#editPermissionModal"
                                        data-user="{{ $user['name'] }}">
                                        Edit Permission
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
