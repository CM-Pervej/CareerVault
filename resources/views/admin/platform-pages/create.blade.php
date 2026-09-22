@extends('layouts.admin.app')

@section('title', 'Create Platform Page | CareerVault')
@section('page_title','Add Platform Pages')

@section('content')

<div class="mx-auto max-w-5xl space-y-6">
    <div>
        <h1 class="cv-admin-title text-3xl sm:text-4xl">Create Platform Page</h1>
        <p class="text-sm text-base-content/60">Add an official page or resource to a job platform.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <div>
                <div class="font-bold">Please check the form.</div>

                <ul class="mt-1 list-inside list-disc text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.platform-pages.store') }}">
        @csrf

        @include('admin.platform-pages._form')
    </form>
</div>
@endsection