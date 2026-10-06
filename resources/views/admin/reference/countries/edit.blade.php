@extends('layouts.admin.app')

@section('title','Edit Country')
@section('page_title', 'Country / Edit / ' . $country->name)

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    <div>
        <div class="mt-3 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                    <i class="fa-solid fa-file-pen text-lg"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-black tracking-tight">Edit {{ $country->name }} </h1>
                    <p class="mt-1 text-sm text-base-content/60">Update the official page information.</p>
                </div>
            </div>

            <a href="{{ route('admin.countries.show',$country) }}" class="btn btn-outline btn-sm gap-2">
                <i class="fa-solid fa-eye"></i> View Country
            </a>
        </div>
    </div>

    <form action="{{ route('admin.countries.update',$country) }}" method="POST">
        @csrf
        @method('PUT')
        
        @include('admin.reference.countries._form')
    </form>
</div>
@endsection