@extends('layouts.app')

@section('title', $company->name)

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold">

                {{ $company->name }}

            </h1>

            <p class="text-gray-500">

                Company Details

            </p>

        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('companies.edit', $company) }}"
                class="btn btn-warning">

                <i class="fa-solid fa-pen"></i>

                Edit

            </a>

            <a
                href="{{ route('companies.index') }}"
                class="btn btn-ghost">

                Back

            </a>

        </div>

    </div>

    <div class="card bg-base-100 shadow border">

        <div class="card-body">

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <span class="font-semibold">Website</span>

                    <div class="mt-1">

                        @if($company->website)

                            <a
                                href="{{ $company->website }}"
                                target="_blank"
                                class="link link-primary">

                                {{ $company->website }}

                            </a>

                        @else

                            -

                        @endif

                    </div>
                </div>

                <div>
                    <span class="font-semibold">Career Page</span>

                    <div class="mt-1">

                        @if($company->career_page)

                            <a
                                href="{{ $company->career_page }}"
                                target="_blank"
                                class="link link-primary">

                                {{ $company->career_page }}

                            </a>

                        @else

                            -

                        @endif

                    </div>
                </div>

                <div>

                    <span class="font-semibold">

                        Email

                    </span>

                    <div class="mt-1">

                        {{ $company->hr_email ?? '-' }}

                    </div>

                </div>

                <div>

                    <span class="font-semibold">

                        Phone

                    </span>

                    <div class="mt-1">

                        {{ $company->phone ?? '-' }}

                    </div>

                </div>

                <div>

                    <span class="font-semibold">

                        Industry

                    </span>

                    <div class="mt-1">

                        {{ $company->industry ?? '-' }}

                    </div>

                </div>

                <div>

                    <span class="font-semibold">

                        Country

                    </span>

                    <div class="mt-1">

                        {{ $company->country ?? '-' }}

                    </div>

                </div>

                <div class="md:col-span-2">

                    <span class="font-semibold">

                        Address

                    </span>

                    <div class="mt-1">

                        {{ $company->address ?? '-' }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card bg-base-100 shadow border mt-8">

        <div class="card-body">

            <h2 class="text-xl font-semibold mb-5">

                Social Links

            </h2>

            @forelse($company->social_links ?? [] as $link)

                <div class="flex justify-between border-b py-3">

                    <span>

                        {{ $link['platform'] }}

                    </span>

                    <a
                        href="{{ $link['url'] }}"
                        target="_blank"
                        class="link link-primary">

                        {{ $link['url'] }}

                    </a>

                </div>

            @empty

                <p class="text-gray-500">

                    No social links added.

                </p>

            @endforelse

        </div>

    </div>

</div>

@endsection