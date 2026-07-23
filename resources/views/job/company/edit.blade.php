@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')

<div class="max-w-5xl mx-auto py-8">

    <div class="mb-6">

        <h1 class="text-3xl font-bold">

            Edit Company

        </h1>

        <p class="text-gray-500 mt-1">

            Update company information.

        </p>

    </div>

    <form
        action="{{ route('companies.update', $company) }}"
        method="POST">

        @csrf
        @method('PUT')

        @include('job.company.partials.form')

    </form>

</div>

@endsection