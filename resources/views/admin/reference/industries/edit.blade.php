@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <div class="breadcrumbs text-sm opacity-60">
                <ul>
                    <li><a href="{{ route('admin.industries.index') }}">Industries</a></li>
                    <li>{{ $industry->name }}</li>
                    <li>Edit</li>
                </ul>
            </div>

            <h1 class="text-2xl font-bold mt-2">Edit Industry</h1>
            <p class="text-sm opacity-60 mt-1">Update industry information and classification.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.industries.show', $industry) }}" class="btn btn-ghost">
                <i class="fa-solid fa-eye"></i>
                View
            </a>

            <a href="{{ route('admin.industries.index') }}" class="btn btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.industries.update', $industry) }}">
        @csrf
        @method('PUT')

        @include('admin.reference.industries._form', ['industry' => $industry])

        <div class="flex justify-end gap-2 mt-6">
            <a href="{{ route('admin.industries.index') }}" class="btn btn-ghost">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-check"></i>
                Update Industry
            </button>
        </div>
    </form>

</div>
@endsection