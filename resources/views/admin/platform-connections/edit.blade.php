@extends('layouts.admin.app')

@section('title','Edit Platform Connections')
@section('page_title','Connect Platforms')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div>
        <h1 class="cv-admin-title text-3xl sm:text-4xl">Edit Platform Connections</h1>
        <p class="mt-1 text-sm text-base-content/60">
            Manage all official platform presences for <span class="font-semibold text-base-content"> {{ $platform->name }} </span> at once.
        </p>
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

    @include('admin.platform-connections._form')
</div>
@endsection