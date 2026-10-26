
@extends('layouts.admin.app')

@section('title', 'Create Ownership Type | CareerVault')
@section('page_title', 'Create Ownership Type')

@section('content')
<div class="space-y-4 p-4 sm:p-6">
    <div>
        <a href="{{ route('admin.ownership-types.index') }}" class="link link-hover text-sm">
            ← Ownership Types
        </a>
        <h1 class="mt-2 text-xl font-bold">Create ownership type</h1>
        <p class="mt-1 text-sm text-base-content/60">
            Add a reusable ownership classification.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.ownership-types.store') }}">
        @include('admin.company.ownership-type._form')
    </form>
</div>
@endsection
