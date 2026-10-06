@extends('layouts.admin.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('admin.cities.index') }}">Cities</a></li>
                    <li>Create</li>
                </ul>
            </div>

            <h1 class="mt-1 text-2xl font-bold">Create City</h1>
            <p class="text-sm text-base-content/60">
                Add a city to the CareerVault reference data.
            </p>
        </div>

        <a href="{{ route('admin.cities.index') }}" class="btn btn-ghost btn-sm">
            <i class="fa-solid fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="card border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.cities.store') }}">
                @csrf

                @include('admin.reference.cities._form')
            </form>
        </div>
    </div>

</div>
@endsection