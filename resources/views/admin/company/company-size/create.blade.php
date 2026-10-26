@extends('layouts.admin.app')

@section('title', 'Create Company Size | CareerVault')
@section('page_title', 'Company Sizes / Create')

@section('content')
    <div class="space-y-5 p-4 sm:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm text-base-content/50">
                    <a href="{{ route('admin.company-sizes.index') }}" class="hover:text-primary">
                        Company Sizes
                    </a>
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                    <span>Create</span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-base-content">
                    Create Company Size
                </h1>

                <p class="mt-1 text-sm text-base-content/60">
                    Add an employee-size category to your company directory.
                </p>
            </div>

            <a href="{{ route('admin.company-sizes.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Company Sizes
            </a>
        </div>

        @include('admin.company.company-size._form')
    </div>
@endsection