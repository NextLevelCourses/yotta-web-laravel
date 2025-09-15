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
@endsection