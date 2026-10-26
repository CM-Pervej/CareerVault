@extends('layouts.admin.app')

@section('title', 'Edit Business Model | CareerVault')
@section('page_title', 'Business Models / Edit')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Edit Business Model</h1>
            <p class="mt-1 text-sm text-base-content/60">Update {{ $businessModel->name }}.</p>
        </div>
        <a href="{{ route('admin.business-models.index') }}" class="btn btn-outline btn-sm">Back to Business Models</a>
    </div>

    <form method="POST" action="{{ route('admin.business-models.update', $businessModel) }}">
        @method('PUT')
        @include('admin.company.business-model._form')
    </form>
</div>
@endsection