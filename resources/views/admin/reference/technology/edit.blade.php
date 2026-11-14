
@extends('layouts.admin.app')
@section('title', 'Edit Technology | CareerVault')
@section('page_title', 'Technology / Edit')
@section('content')
<div class="space-y-3">
    <div class="relative overflow-hidden bg-base-100 p-5 sm:p-7">
        <div class="pointer-events-none absolute -right-16 -top-20 size-64 rounded-full bg-primary/5 blur-3xl"></div>
        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="grid size-14 shrink-0 place-items-center rounded-2xl bg-primary/10 text-primary ring-1 ring-primary/15">
                <i class="fa-solid fa-code text-2xl"></i>
            </div>
            <div class="min-w-0 flex-1">
                <div class="mb-1 flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.16em] text-primary">Technology Management</span>
                    <span class="size-1 rounded-full bg-base-content/30"></span>
                    <span class="text-xs text-base-content/50">Edit Configuration</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-base-content sm:text-3xl">Update Technology</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-base-content/60">Update technology information, category, appearance, and visibility across CareerVault.</p>
            </div>
            <div class="hidden shrink-0 sm:block">
                <div class="rounded-xl border border-base-300 bg-base-200/50 px-4 py-3 text-center">
                    <a href="{{ route('admin.technology.show', $technology) }}" class="btn btn-outline btn-sm">
                        <i class="fa-solid fa-eye"></i> View Details
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('admin.reference.technology._form', ['technology' => $technology, 'categories' => $categories])
</div>
@endsection