@extends('layouts.admin.app')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================== PAGE HEADER / HERO ========================== --}}
    <section class="relative overflow-hidden rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        {{-- Decorative background --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full opacity-10 blur-3xl"
                @if($industry->color)
                    style="background-color: {{ $industry->color }}"
                @else
                    style="background-color: #6366f1"
                @endif
            ></div>
            <div class="absolute -bottom-32 -left-20 h-64 w-64 rounded-full bg-primary/5 blur-3xl"></div>
        </div>

        <div class="relative p-5 sm:p-7">
            {{-- Breadcrumb --}}
            <div class="mb-5 flex flex-wrap items-center gap-2 text-xs text-base-content/50">
                <a href="{{ route('admin.industries.index') }}" class="transition hover:text-primary">Industries</a>
                <i class="fa-solid fa-chevron-right text-[9px]"></i>

                @if($industry->parent)
                    <a href="{{ route('admin.industries.show', $industry->parent) }}" class="transition hover:text-primary"> {{ $industry->parent->name }} </a>
                    <i class="fa-solid fa-chevron-right text-[9px]"></i>
                @endif

                <span class="text-base-content/70"> {{ $industry->name }} </span>
            </div>

            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                {{-- Identity --}}
                <div class="flex min-w-0 items-center gap-4">
                    <div
                        class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl border border-base-300 bg-base-200/70 text-2xl shadow-sm"
                        @if($industry->color)
                            style="color: {{ $industry->color }}"
                        @else
                            style="color: var(--fallback-p,oklch(var(--p)))"
                        @endif
                    >
                        <i class="{{ $industry->icon ?: 'fa-solid fa-layer-group' }}"></i>
                    </div>

                    <div class="min-w-0">
                        <div class="mb-1 flex flex-wrap items-center gap-2">
                            @if($industry->trashed())
                                <span class="badge badge-warning badge-outline gap-1.5">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i> Trashed
                                </span>
                            @elseif($industry->is_active)
                                <span class="badge badge-success badge-outline gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-success"></span> Active
                                </span>
                            @else
                                <span class="badge badge-ghost gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-base-content/30"></span> Inactive
                                </span>
                            @endif

                            <span class="badge badge-outline"> {{ $industry->parent ? 'Sub-industry' : 'Top-level' }} </span>
                        </div>

                        <h1 class="truncate text-2xl font-bold tracking-tight sm:text-3xl"> {{ $industry->name }} </h1>

                        <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-base-content/50">
                            <span class="font-mono"> {{ $industry->slug }} </span>

                            @if($industry->parent)
                                <span class="hidden sm:inline">•</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <i class="fa-solid fa-sitemap text-[10px]"></i> Child of {{ $industry->parent->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-2 lg:justify-end">
                    @if(!$industry->trashed())
                        <a href="{{ route('admin.industries.edit', $industry) }}" class="btn btn-primary gap-2">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Edit Industry</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.industries.index') }}" class="btn btn-ghost border border-base-300 gap-2">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== MAIN CONTENT ========================= --}}
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">
        {{-- ==================== LEFT / MAIN ======================== --}}
        <div class="space-y-5 lg:col-span-2 order-2 sm:order-1">
            @if ($industry->parent)
                @include('admin.reference.industries.partials.history', ['part' => "grid-cols-2"])
            @endif

            {{-- ===================== SUB-INDUSTRIES ======================= --}}
            @if($industry->children->count())
                <section class="overflow-hidden rounded-sm border border-base-300 bg-base-100 shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-base-300 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-secondary/10 text-secondary">
                                <i class="fa-solid fa-sitemap"></i>
                            </div>

                            <div>
                                <h2 class="font-bold">Sub-industries</h2>
                                <p class="text-xs text-base-content/50">Industries belonging to {{ $industry->name }} </p>
                            </div>
                        </div>

                        <span class="badge badge-primary badge-outline">
                            {{ number_format($industry->children->count()) }}
                            {{ $industry->children->count() === 1 ? 'industry' : 'industries' }}
                        </span>
                    </div>

                    <div class="divide-y divide-base-300">
                        @foreach($industry->children as $child)
                            <a href="{{ route('admin.industries.show', $child) }}" class="group flex items-center gap-4 px-5 py-4 transition hover:bg-base-200/50 sm:px-6">
                                {{-- Icon --}}
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-base-200 text-lg transition group-hover:scale-105"
                                    @if($child->color)
                                        style="color: {{ $child->color }}"
                                    @endif
                                >
                                    <i class="{{ $child->icon ?: 'fa-solid fa-layer-group' }}"></i>
                                </div>

                                {{-- Name --}}
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="truncate font-semibold group-hover:text-primary"> {{ $child->name }} </span>

                                        @if($child->is_active)
                                            <span class="badge badge-success badge-xs badge-outline">Active</span>
                                        @else
                                            <span class="badge badge-ghost badge-xs">Inactive</span>
                                        @endif
                                    </div>

                                    <p class="mt-0.5 truncate font-mono text-xs text-base-content/45"> {{ $child->slug }} </p>
                                </div>

                                {{-- Sort --}}
                                <div class="hidden text-right sm:block">
                                    <p class="text-[10px] font-medium uppercase tracking-wider text-base-content/40">Order</p>
                                    <p class="text-sm font-semibold"> {{ $child->sort_order }} </p>
                                </div>

                                <i class="fa-solid fa-chevron-right text-xs text-base-content/30 transition group-hover:translate-x-0.5 group-hover:text-primary"></i>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        {{-- ==================== RIGHT / SIDEBAR ===================== --}}
        <aside class="space-y-5 order-1 sm:order-2">
            {{-- Hierarchy --}}
            <section class="rounded-sm border border-base-300 bg-base-100 shadow-sm">
                <div class="border-b border-base-300 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-info/10 text-info">
                            <i class="fa-solid fa-diagram-project"></i>
                        </div>

                        <div>
                            <h2 class="font-bold">Hierarchy</h2>
                            <p class="text-xs text-base-content/50">Industry structure</p>
                        </div>
                    </div>
                </div>

                <div class="p-5">
                    @if($industry->parent)
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-base-200">
                                <i class="{{ $industry->parent->icon ?: 'fa-solid fa-layer-group' }}"
                                    @if($industry->parent->color)
                                        style="color: {{ $industry->parent->color }}"
                                    @endif
                                ></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-[10px] font-medium uppercase tracking-wider text-base-content/40">Parent</p>
                                <a href="{{ route('admin.industries.show', $industry->parent) }}" class="truncate font-semibold text-primary hover:underline"> {{ $industry->parent->name }} </a>
                            </div>
                        </div>

                        <div class="ml-5 h-6 border-l border-dashed border-base-300"></div>
                    @endif

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary"
                            @if($industry->color)
                                style="color: {{ $industry->color }}"
                            @endif
                        >
                            <i class="{{ $industry->icon ?: 'fa-solid fa-layer-group' }}"></i>
                        </div>

                        <div class="min-w-0">
                            <p class="text-[10px] font-medium uppercase tracking-wider text-base-content/40">Current / {{ $industry->parent ? 'Sub-industry' : 'Top-level' }} </p>
                            <p class="truncate font-semibold"> {{ $industry->name }} </p>
                        </div>
                    </div>

                    @if($industry->children->count())
                        <div class="ml-5 h-6 border-l border-dashed border-base-300"></div>
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-secondary/10 text-secondary">
                                <i class="fa-solid fa-sitemap"></i>
                            </div>

                            <div>
                                <p class="text-[10px] font-medium uppercase tracking-wider text-base-content/40">Children / {{ !$industry->parent ? 'Sub-industry' : 'Top-level' }} </p>
                                <p class="font-semibold">
                                    {{ $industry->children->count() }}
                                    {{ $industry->children->count() === 1 ? 'sub-industry' : 'sub-industries' }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>


            {{-- Trash --}}
            @if (!$industry->parent)
                @include('admin.reference.industries.partials.history', ['part' => "grid-cols-1"])
                @include('admin.reference.industries.partials.trash')
            @endif
        </aside>
    </div>

    {{-- Trash --}}
    @if ($industry->parent)
        @include('admin.reference.industries.partials.trash')
    @endif
</div>
@endsection