@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-base-content/60 mb-2">
            <a href="{{ route('platforms.index') }}" class="hover:text-primary">Platforms</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="text-3xl font-bold">Create Platform</h1>
        <p class="text-base-content/60 mt-1">Add a new job or professional platform.</p>
    </div>

    <form action="{{ route('platforms.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('job.platform.partials.form')
    </form>
</div>
@endsection