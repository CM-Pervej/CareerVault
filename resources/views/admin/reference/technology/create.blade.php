@extends('layouts.admin.app')

@section('title', 'Create Technology | CareerVault')
@section('page_title', 'Technology / Create')

@section('content')
<div class="space-y-4 p-4 sm:p-6">

    <x-admin.ui.page-header
        title="Create Technology"
        description="Add a technology to your CareerVault technology reference."
        icon="fa-solid fa-code"
    />

    <form method="POST" action="{{ route('admin.technology.store') }}">
        @csrf

        @include('admin.reference.technology._form', ['technology' => null])

        <div class="sticky bottom-0 z-10 mt-6 flex items-center justify-between gap-3 border-t border-base-300 bg-base-100/95 px-4 py-4 backdrop-blur sm:px-6">
            <a href="{{ route('admin.technology.index') }}" class="btn btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Create Technology
            </button>
        </div>
    </form>

</div>
@endsection