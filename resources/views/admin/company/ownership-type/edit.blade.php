
@extends('layouts.admin.app')

@section('title', 'Edit Ownership Type | CareerVault')
@section('page_title', 'Edit Ownership Type')

@section('content')
<div class="space-y-4 p-4 sm:p-6">
    <div>
        <a href="{{ route('admin.ownership-types.index') }}" class="link link-hover text-sm">
            ← Ownership Types
        </a>
        <h1 class="mt-2 text-xl font-bold">Edit ownership type</h1>
        <p class="mt-1 text-sm text-base-content/60">
            Update {{ $ownershipType->name }}.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.ownership-types.update', $ownershipType) }}">
        @method('PUT')
        @include('admin.company.ownership-type._form')
    </form>
</div>
@endsection
