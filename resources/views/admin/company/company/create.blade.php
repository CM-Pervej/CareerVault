
@extends('layouts.admin.app')

@section('title', 'Create Company | CareerVault')
@section('page_title', 'Companies / Create')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="text-sm text-base-content/60">Company Directory</div>
            <h1 class="text-2xl font-bold">Create Company</h1>
            <p class="mt-1 text-sm text-base-content/60">Add a company and organize its profiles, classifications, and locations.</p>
        </div>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-ghost">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Companies
        </a>
    </div>

    @include('admin.company.company._form')
</div>
@endsection
