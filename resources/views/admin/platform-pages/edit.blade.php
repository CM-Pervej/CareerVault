@extends('layouts.admin.app')

@section('title', 'Edit '.$platformPage->name.' | CareerVault')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">

    <div>

        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <a href="{{ route('admin.platform-pages.index') }}">
                        Platform Pages
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.platform-pages.show', $platformPage) }}">
                        {{ $platformPage->name }}
                    </a>
                </li>

                <li>Edit</li>
            </ul>
        </div>

        <div class="mt-3 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">

            <div>
                <h1 class="text-2xl font-black tracking-tight">
                    Edit Platform Page
                </h1>

                <p class="mt-1 text-sm text-base-content/60">
                    Update the official page information.
                </p>
            </div>

            <a
                href="{{ route('admin.platform-pages.show', $platformPage) }}"
                class="btn btn-outline btn-sm gap-2"
            >
                <i class="fa-solid fa-eye"></i>
                View Page
            </a>

        </div>

    </div>

    <form
        method="POST"
        action="{{ route('admin.platform-pages.update', $platformPage) }}"
    >
        @csrf
        @method('PUT')

        @include('admin.platform-pages._form')
    </form>

</div>

@endsection