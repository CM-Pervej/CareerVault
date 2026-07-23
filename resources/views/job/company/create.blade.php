@extends('layouts.app')

@section('title', 'Add Company')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <div class="mb-6">

        <h1 class="text-3xl font-bold">

            Add Company

        </h1>

        <p class="text-gray-500 mt-1">

            Create a new company profile.

        </p>

    </div>

    <form
        action="{{ route('companies.store') }}"
        method="POST" autocomplete="off">

        @include('job.company.partials.form')

    </form>

</div>

@endsection