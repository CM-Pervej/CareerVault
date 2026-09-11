@extends('layouts.admin.app')

@section('title','Add Platform | CareerVault')
@section('page_title','Add Platform')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">

    <div>
        <div class="cv-admin-label mb-2 text-primary">
            Directory / Platforms / Create
        </div>

        <h1 class="cv-admin-title text-3xl sm:text-4xl">
            Add Platform
        </h1>

        <p class="mt-1 text-sm text-base-content/60">
            Add a new job platform to the CareerVault directory.
        </p>
    </div>

    <form
        method="POST"
        action="{{ route('admin.platforms.store') }}"
    >
        @csrf

        @include('admin.platforms._form')

    </form>

</div>
@endsection