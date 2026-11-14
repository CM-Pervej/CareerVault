@extends('layouts.admin.app')

@section('title', 'Create Ownership Type | CareerVault')
@section('page_title', 'Create Ownership Type')

@section('content')
<div class="space-y-3">
    <div class="relative overflow-hidden bg-base-100 p-5 sm:p-7">
        <div class="pointer-events-none absolute -right-16 -top-20 size-64 rounded-full bg-primary/5 blur-3xl"></div>

        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="grid size-14 shrink-0 place-items-center rounded-2xl bg-primary/10 text-primary ring-1 ring-primary/15">
                <i class="fa-solid fa-building text-2xl"></i>
            </div>

            <div class="min-w-0 flex-1">
                <div class="mb-1 flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">Company Management</span>
                    <span class="size-1 rounded-full bg-base-content/30"></span>
                    <span class="text-xs text-base-content/50">New Configuration</span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-base-content sm:text-3xl">Create ownership type</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-base-content/60">Add a reusable ownership classification across your CareerVault platform.</p>
            </div>

            <div class="hidden shrink-0 sm:block">
                <div class="rounded-xl border border-base-300 bg-base-200/50 px-4 py-3 text-center">
                    <a href="{{ route('admin.ownership-types.index') }}" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('admin.company.ownership-type._form')
</div>
@endsection