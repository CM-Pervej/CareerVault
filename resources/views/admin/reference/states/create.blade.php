@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

        <div>
            <div class="breadcrumbs text-sm opacity-60">
                <ul>
                    <li>
                        <a href="{{ route('admin.countries.index') }}">
                            Countries
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.countries.show', $country) }}">
                            {{ $country->name }}
                        </a>
                    </li>
                    <li>States</li>
                    <li>Create</li>
                </ul>
            </div>

            <h1 class="text-2xl font-bold mt-2">
                Add State / Division
            </h1>

            <p class="text-sm opacity-60 mt-1">
                Add an administrative subdivision of {{ $country->name }}.
            </p>
        </div>

        <a
            href="{{ route('admin.countries.states.index', $country) }}"
            class="btn btn-ghost"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Back
        </a>

    </div>

    <form method="POST" action="{{ route('admin.countries.states.store', $country) }}">
        @csrf

        @include('admin.reference.states._form')

        <div class="flex justify-end gap-2 mt-6">
            <a
                href="{{ route('admin.countries.states.index', $country) }}"
                class="btn btn-ghost"
            >
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Create State
            </button>
        </div>
    </form>

</div>
@endsection