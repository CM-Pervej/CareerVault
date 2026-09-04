@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-base-content/60 mb-2">
            <a href="{{ route('platforms.index') }}" class="hover:text-primary">Platforms</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="text-3xl font-bold">Edit Platform</h1>
        <p class="text-base-content/60 mt-1">Update platform information and settings.</p>
    </div>

    <form action="{{ route('platforms.update', $platform) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('job.platform.partials.form')
    </form>
</div>
@endsection