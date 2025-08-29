@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Monitoring Air Quality</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Air Quality</li>
                    </ol>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">&laquo; Kembali</a>
        </div>
    </div>

    <livewire:air-quality-liveware />
@endsection
