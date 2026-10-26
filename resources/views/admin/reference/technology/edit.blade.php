@extends('layouts.admin.app')

@section('title', 'Edit Technology | CareerVault')
@section('page_title', 'Technology / Edit')

@section('content')
<div class="space-y-4 p-4 sm:p-6">

    <x-admin.ui.page-header
        title="Edit Technology"
        description="Update the technology information and settings."
        icon="fa-solid fa-pen-to-square"
    />

    <form method="POST" action="{{ route('admin.technology.update', $technology) }}">
        @csrf
        @method('PUT')

        @include('admin.reference.technology._form', ['technology' => $technology])

        <div class="sticky bottom-0 z-10 mt-6 flex items-center justify-between gap-3 border-t border-base-300 bg-base-100/95 px-4 py-4 backdrop-blur sm:px-6">
            <a href="{{ route('admin.technology.index') }}" class="btn btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                Update Technology
            </button>
        </div>
    </form>

</div>
@endsection