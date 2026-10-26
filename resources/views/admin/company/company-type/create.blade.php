
@extends('layouts.admin.app')

@section('title', 'Create Company Type | CareerVault')
@section('page_title', 'Company Types / Create')

@section('content')
    <div class="space-y-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold">Create Company Type</h1>
                <p class="mt-1 text-sm text-base-content/60">
                    Add a new business organization category.
                </p>
            </div>

            <a href="{{ route('admin.company-types.index') }}" class="btn btn-outline btn-sm">
                Back to Company Types
            </a>
        </div>

        @include('admin.company.company-type._form')
    </div>
@endsection
