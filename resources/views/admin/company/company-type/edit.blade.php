
@extends('layouts.admin.app')

@section('title', 'Edit Company Type | CareerVault')
@section('page_title', 'Company Types / Edit')

@section('content')
    <div class="space-y-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold">Edit Company Type</h1>
                <p class="mt-1 text-sm text-base-content/60">
                    Update {{ $companyType->name }}.
                </p>
            </div>

            <a href="{{ route('admin.company-types.show', $companyType) }}" class="btn btn-outline btn-sm">
                View Details
            </a>
        </div>

        @include('admin.company.company-type._form')
    </div>
@endsection
