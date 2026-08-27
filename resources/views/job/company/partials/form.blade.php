@php
    $isEdit = isset($company) && $company;
@endphp

<div>
    <section class="mb-8">
        {{-- Breadcrumb --}}
        <div class="breadcrumbs text-sm mb-3 hidden sm:block">
            <ul>
                <li> <a href="{{ route('dashboard') }}"> <i class="fa-solid fa-house mr-1"></i> Dashboard </a> </li>
                <li> <a href="{{ route('companies.index') }}"> Companies </a> </li>
                @if($isEdit)
                    <li class="font-semibold">{{ $company->name }}</li>
                @endif
                <li class="font-semibold"> {{ $isEdit ? 'Edit' : 'Create' }} </li>
            </ul>
        </div>

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative overflow-hidden sm:rounded-lg bg-gradient-to-br from-indigo-600 via-indigo-600 to-violet-700 px-5 py-6 sm:px-8 sm:py-8 shadow-lg shadow-indigo-900/10">
            <div>
                <h1 class="text-3xl font-bold text-white"> {{ $isEdit ? 'Edit Company' : 'Create Company' }} </h1>

                <p class="text-base-content/70 mt-2 text-white">
                    {{ $isEdit ? 'Update company information.' : 'Add a new company to CareerVault.' }}
                </p>
            </div>

            <a href="{{ route('companies.index') }}" class="btn btn-outline mt-4 md:mt-0"> <i class="fa-solid fa-arrow-left"></i> Back </a>
        </div>
    </section>

    @if($isEdit)
        <form action="{{ route('companies.update', $company) }}" method="POST">
            @method('PUT')

    @else
        <form action="{{ route('companies.store') }}" method="POST">

    @endif
        @csrf
        <div class="space-y-1 sm:space-y-6">
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
            {{-- @include('job.company.partials.social-links') --}}

            @include('job.company.partials.platform')

            {{-- Actions --}}
            @include('job.company.partials.actions')
        </div>
    </form>
</div>