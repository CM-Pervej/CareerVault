@extends('layouts.admin.app')

@section('title', 'Create Platform Group | CareerVault')
@section('page_title','Groups / Add')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    <div>
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                <i class="fa-solid fa-users text-lg"></i>
            </div>

            <div>
                <h1 class="cv-admin-title text-3xl sm:text-4xl">Create Platform Group</h1>
                <p class="text-sm text-base-content/60">Add an official group or resource to a job platform.</p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation mt-0.5 shrink-0"></i>

                <div>
                    <div class="font-bold">Please check the form.</div>

                    <ul class="mt-1 list-inside list-disc text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.platform-groups.store') }}" enctype="multipart/form-data">
        @csrf

        @include('admin.platform-groups._form')
    </form>
</div>
@endsection