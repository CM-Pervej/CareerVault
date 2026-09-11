@extends('layouts.admin.app')

@section('title',"Edit {$user->name} | CareerVault")
@section('page_title','Edit User')

@section('content')
<div class="mx-auto max-w-5xl space-y-5">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="cv-avatar h-14 w-14 bg-primary/10 text-xl text-primary">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>

            <div>
                <div class="cv-admin-label mb-1 text-primary">
                    People / Users / Edit
                </div>

                <h1 class="cv-admin-title text-3xl">
                    {{ $user->name }}
                </h1>

                <p class="mt-1 text-sm text-base-content/50">
                    {{ $user->email }}
                </p>
            </div>

        </div>

        <div class="flex gap-2 self-start sm:self-auto">

            <a
                href="{{ route('admin.users.show',$user) }}"
                class="btn btn-ghost gap-2"
            >
                <i class="fa-regular fa-eye"></i>
                View
            </a>

            <a
                href="{{ route('admin.users.index') }}"
                class="btn btn-ghost btn-square"
                title="Back to Users"
            >
                <i class="fa-solid fa-arrow-left"></i>
            </a>

        </div>

    </div>

    <form
        method="POST"
        action="{{ route('admin.users.update',$user) }}"
        class="space-y-5"
    >
        @csrf
        @method('PUT')

        @include('admin.users._form')

        <div class="flex flex-col-reverse gap-3 border-t border-base-300 pt-5 sm:flex-row sm:justify-end">

            <a
                href="{{ route('admin.users.show',$user) }}"
                class="btn btn-ghost"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="btn btn-primary gap-2"
            >
                <i class="fa-solid fa-floppy-disk"></i>
                Save Changes
            </button>

        </div>
    </form>

</div>
@endsection