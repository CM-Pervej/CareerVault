@extends('layouts.admin.app')

@section('title',"Edit {$platform->name} | CareerVault")
@section('page_title','Edit Platform')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <div class="cv-admin-label mb-2 text-primary">
                Directory / Platforms / Edit
            </div>

            <h1 class="cv-admin-title text-3xl sm:text-4xl">
                Edit Platform
            </h1>

            <p class="mt-1 text-sm text-base-content/60">
                Update the information and visibility of
                <span class="font-medium text-base-content">
                    {{ $platform->name }}
                </span>.
            </p>
        </div>

        <a
            href="{{ route('admin.platforms.show',$platform) }}"
            class="btn btn-ghost btn-sm gap-2 self-start"
        >
            <i class="fa-regular fa-eye"></i>
            View Platform
        </a>

    </div>

    <form
        method="POST"
        action="{{ route('admin.platforms.update',$platform) }}"
    >
        @csrf
        @method('PUT')

        @include('admin.platforms._form')

    </form>

</div>
@endsection