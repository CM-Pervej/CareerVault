@extends('layouts.admin.app')

@section('title', 'Edit Company Size | CareerVault')
@section('page_title', 'Company Sizes / Edit')

@section('content')
    <div class="space-y-5 p-4 sm:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm text-base-content/50">
                    <a href="{{ route('admin.company-sizes.index') }}" class="hover:text-primary">
                        Company Sizes
                    </a>
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-base-content">
                    Edit Company Size
                </h1>

                <p class="mt-1 text-sm text-base-content/60">
                    Update the employee range and settings for {{ $companySize->name }}.
                </p>
            </div>

            <a href="{{ route('admin.company-sizes.show', $companySize) }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-eye"></i>
                View Details
            </a>
        </div>

        @include('admin.company.company-size._form', ['companySize' => $companySize])
    </div>
@endsection