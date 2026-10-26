
@extends('layouts.admin.app')

@section('title', 'Edit ' . $company->name . ' | CareerVault')
@section('page_title', 'Companies / Edit')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="text-sm text-base-content/60">Company Directory / {{ $company->name }}</div>
            <h1 class="text-2xl font-bold">Edit Company</h1>
            <p class="mt-1 text-sm text-base-content/60">Update company information and linked profiles.</p>
        </div>
        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-ghost">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Details
        </a>
    </div>

    @include('admin.company.company._form')
</div>
@endsection
