@php
    $isEdit = isset($company) && $company;
@endphp

<div>

    {{-- Breadcrumb --}}
    <div class="breadcrumbs text-sm">
        <ul>
            <li> <a href="{{ route('dashboard') }}"> <i class="fa-solid fa-house mr-1"></i> Dashboard </a> </li>
            <li> <a href="{{ route('companies.index') }}"> Companies </a> </li>
            <li class="font-semibold"> {{ $isEdit ? 'Edit' : 'Create' }} </li>
        </ul>
    </div>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold"> {{ $isEdit ? 'Edit Company' : 'Create Company' }} </h1>

            <p class="text-base-content/70 mt-2">
                {{ $isEdit
                    ? 'Update company information.'
                    : 'Add a new company to CareerVault.' }}
            </p>
        </div>

        <a href="{{ route('companies.index') }}" class="btn btn-outline mt-4 md:mt-0"> <i class="fa-solid fa-arrow-left"></i> Back </a>
    </div>

    @if($isEdit)
        <form action="{{ route('companies.update', $company) }}" method="POST">
            @method('PUT')

    @else
        <form action="{{ route('companies.store') }}" method="POST">

    @endif
        @csrf
        <div class="space-y-6">
            {{-- Company Information --}}
            @include('job.company.partials.company-info')

            {{-- Countries & Industries --}}
            @include('job.company.partials.classification')

            {{-- Emails --}}
            @include('job.company.partials.emails')

            {{-- Phones --}}
            @include('job.company.partials.phones')

            {{-- Addresses --}}
            @include('job.company.partials.addresses')

            {{-- Social Links --}}
            @include('job.company.partials.social-links')

            {{-- Actions --}}
            @include('job.company.partials.actions')
        </div>
    </form>
</div>