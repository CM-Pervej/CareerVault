@extends('layouts.admin.app')

@section('title','Create User | CareerVault')
@section('page_title','Create User')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <div class="cv-admin-label mb-2 text-primary">
                People / Users
            </div>

            <h1 class="cv-admin-title text-3xl sm:text-4xl">
                Create User
            </h1>

            <p class="mt-1 text-sm text-base-content/60">
                Create a new CareerVault account and configure its access.
            </p>
        </div>

        <a
            href="{{ route('admin.users.index') }}"
            class="btn btn-ghost gap-2 self-start sm:self-auto"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Back to Users
        </a>

    </div>

    <form
        method="POST"
        action="{{ route('admin.users.store') }}"
        class="space-y-5"
    >
        @csrf

        @include('admin.users._form')

        <div class="flex flex-col-reverse gap-3 border-t border-base-300 pt-5 sm:flex-row sm:justify-end">

            <a
                href="{{ route('admin.users.index') }}"
                class="btn btn-ghost"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary gap-2"
            >
                <i class="fa-solid fa-user-plus"></i>
                Create User
            </button>

        </div>
    </form>

</div>
@endsection