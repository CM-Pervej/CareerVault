@extends('layouts.admin.app')

@section('title', 'Edit Platform Group')
@section('page_title', 'Groups / Edit / ' . $platformGroup->name)

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    <div>
        <div class="mt-3 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                    <i class="fa-solid fa-file-pen text-lg"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-black tracking-tight">Edit {{ $platformGroup->name }} </h1>
                    <p class="mt-1 text-sm text-base-content/60">Update the official group information.</p>
                </div>
            </div>

            <a href="{{ route('admin.platform-groups.show', $platformGroup) }}" class="btn btn-outline btn-sm gap-2">
                <i class="fa-solid fa-eye"></i> View Group
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.platform-groups.update', $platformGroup) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.platform-groups._form')
    </form>
</div>
@endsection