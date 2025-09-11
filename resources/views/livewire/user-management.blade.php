<div class="card">
    <div class="card-header">
        <h4 class="card-title">Manajemen User</h4>
        <p class="card-title-desc">Kelola user terdaftar dan ubah peran mereka.</p>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped mb-0">
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
                    @foreach ($users as $user)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge {{ $user->isAdmin() ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if ($user->id !== auth()->id()) {{-- Admin tidak bisa mengubah perannya sendiri --}}
                                    @if ($user->isAdmin())
                                        <button wire:click="changeRole({{ $user->id }}, 'user')" class="btn btn-sm btn-warning">Jadikan User</button>
                                    @else
                                        <button wire:click="changeRole({{ $user->id }}, 'admin')" class="btn btn-sm btn-info">Jadikan Admin</button>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
