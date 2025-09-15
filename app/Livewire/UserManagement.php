<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public function changeRole(User $user, string $role)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Pastikan hanya admin yang bisa melakukan ini
        if (!$currentUser || !$currentUser->isAdmin()) {
            abort(403);
        }

        // Pastikan peran yang diinput valid
        if (in_array($role, ['admin', 'user'])) {
            $user->role = $role;
            $user->save();
            session()->flash('success', 'Peran user berhasil diubah.');
        }
    }

    public function render()
    {
        // Bagian ini mengambil data user dan mengirimkannya ke view
        return view('livewire.user-management', [
            'users' => User::latest()->paginate(10),
        ]);
    }
}
