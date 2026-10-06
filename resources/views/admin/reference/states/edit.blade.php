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
                    <li>
                        <a href="{{ route('admin.countries.states.index', $country) }}">
                            States
                        </a>
                    </li>
                    <li>Edit</li>
                </ul>
            </div>

            <h1 class="text-2xl font-bold mt-2">
                Edit {{ $state->name }}
            </h1>

            <p class="text-sm opacity-60 mt-1">
                Update state / division information.
            </p>
        </div>

        <div class="flex gap-2">
            <a
                href="{{ route('admin.countries.states.show', [$country, $state]) }}"
                class="btn btn-ghost"
            >
                <i class="fa-solid fa-eye"></i>
                View
            </a>

            <a
                href="{{ route('admin.countries.states.index', $country) }}"
                class="btn btn-ghost"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>

    </div>

    <form
        method="POST"
        action="{{ route('admin.countries.states.update', [$country, $state]) }}"
    >
        @csrf
        @method('PUT')

        @include('admin.reference.states._form', ['state' => $state])

        <div class="flex justify-end gap-2 mt-6">
            <a
                href="{{ route('admin.countries.states.index', $country) }}"
                class="btn btn-ghost"
            >
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-check"></i>
                Update State
            </button>
        </div>
    </form>

</div>
@endsection