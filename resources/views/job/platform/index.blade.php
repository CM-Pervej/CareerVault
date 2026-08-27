@extends('layouts.app')

@section('title','Platforms | CareerVault')

@section('content')
<div>
    {{-- Header --}}
    <section class="sm:mb-2">
        {{-- Hero --}}
        <div class="relative overflow-hidden sm:rounded-lg bg-gradient-to-br from-indigo-600 via-indigo-600 to-violet-700 px-4 py-5 sm:px-8 sm:py-8 shadow-lg shadow-indigo-900/10">
            {{-- Decorative pattern (inline SVG, no CSS) --}}
            <svg class="absolute -top-10 -right-10 w-48 h-48 sm:w-64 sm:h-64 opacity-10 pointer-events-none" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="99" stroke="white" stroke-width="1.5"/>
                <circle cx="100" cy="100" r="72" stroke="white" stroke-width="1.5"/>
                <circle cx="100" cy="100" r="45" stroke="white" stroke-width="1.5"/>
            </svg>

            <div class="relative flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 sm:gap-6">
                <div class="min-w-0">
                    <p class="text-xs font-semibold tracking-widest uppercase text-indigo-200 mb-2 truncate">
                        <i class="fa-solid fa-earth-americas mr-1"></i>
                        <a href="{{ route('dashboard') }}">CareerVault</a> /
                    </p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-white">Platforms</h1>
                        <sup id="platformTotal" class="rounded-full bg-white/20 text-white text-xs font-bold px-2 py-1 leading-none border border-white/30"> {{ $platforms->total() }} </sup>
                    </div>
                    <p class="text-indigo-100/80 text-sm max-w-2xl mt-1">Manage job platforms and keep your job sources organized across your directory.</p>

                    {{-- Quick stats, computed from the full platform list --}}
                    <div class="flex flex-wrap items-center gap-2 mt-4 sm:mt-5">
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-earth-americas text-indigo-200 text-xs"></i>
                            <span class="text-white text-sm font-semibold">{{ $totalPlatform }}</span>
                            <span class="text-indigo-200 text-xs">total</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-list-ol text-indigo-200 text-xs"></i>
                            <span id="platformStatShowing" class="text-white text-sm font-semibold">{{ $platforms->total() ? "{$platforms->firstItem()}–{$platforms->lastItem()}" : '0' }}</span>
                            <span class="text-indigo-200 text-xs">showing</span>
                        </div>
                        <div class="flex items-center gap-2 rounded-lg bg-white/10 border border-white/15 px-3 py-2">
                            <i class="fa-solid fa-layer-group text-indigo-200 text-xs"></i>
                            <span id="companyStatPage" class="text-white text-sm font-semibold">{{ $platforms->currentPage() }}/{{ max($platforms->lastPage(), 1) }}</span>
                            <span class="text-indigo-200 text-xs">page</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full lg:w-auto">
                    {{-- Search (ids / data-url / value unchanged for existing JS) --}}
                    <div class="w-full sm:w-auto">
                        @include('modular.database_filter', [
                            'id' => 'platformSearch',
                            'url' => route('platforms.index'),
                            'placeholder' => 'Search platform...',
                            'loadingId' => 'platformSearchLoading',
                            'shortcutId' => 'platformSearchShortcut',
                        ])
                    </div>

                    {{-- Hidden on the smallest screens; the floating button below covers mobile --}}
                    <button onclick="platformModal.showModal()" class="hidden sm:inline-flex items-center justify-center gap-2 rounded-sm bg-white hover:bg-indigo-50 text-indigo-700 text-sm font-medium px-4 py-2.5 shadow-sm transition shrink-0 cursor-pointer">
                        <i class="fa-solid fa-plus"></i> Add Platform
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Platform Table --}}
    <div class="card bg-transparent sm:bg-base-100 sm:shadow sm:border sm:border-base-300 overflow-hidden p-2 sm:p-0">
        <div class="p-2">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead class="hidden md:table-header-group">
                        <tr class="cv-eyebrow border-b border-base-300">
                            <th class="w-10">#</th>
                            <th>Platform</th> {{-- <th>Slug</th> --}} <th>Icon</th>
                            <th>Color</th> <th>Base URL</th> <th>Job URL</th>
                            <th>Job Type</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="platformContainer" class="block md:table-row-group">
                        @forelse($platforms as $item)
                            <tr class="crud-row platform-card block md:table-row rounded-xl md:rounded-none border md:border-0 md:border-b border-slate-200 border-b-base-300 last:border-0 mb-3 md:mb-0 py-3 px-1.5 md:p-0 bg-white md:bg-transparent shadow-sm md:shadow-none hover:bg-slate-50">
                                {{-- Number --}}
                                <td class="hidden md:table-cell cv-mono text-xs opacity-40 py-2.5 text-center">
                                    {{ str_pad($loop->iteration + (($platforms->currentPage() - 1) * $platforms->perPage()), 2, '0', STR_PAD_LEFT) }}
                                </td>

                                {{-- Platform --}}
                                <td class="block md:table-cell px-0 md:px-4 py-1 md:py-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="{{ $item->color ? 'background-color:'.$item->color.'20;color:'.$item->color : '' }}">
                                            @if($item->icon)
                                                <i class="{{ $item->icon }} text-lg"></i>
                                            @else
                                                <i class="fa-solid fa-globe text-base-content/40"></i>
                                            @endif
                                        </div>

                                        <span class="font-medium truncate">
                                            {{ $item->name }}
                                        </span>

                                    </div>
                                </td>

                                {{-- Slug --}}
                                {{-- <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Slug'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    <span class="badge badge-ghost">{{ $item->slug ?? '-' }}</span>
                                </td> --}}

                                {{-- Icon --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Icon'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    <span class="truncate max-w-[60%] text-right md:text-left">{{ $item->icon ?? '-' }}</span>
                                </td>

                                {{-- Color --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Color'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    @if($item->color)
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded border border-base-300 shrink-0" style="background-color:{{ $item->color }}" title="{{ $item->color }}"></span>

                                            <code class="text-xs"> {{ $item->color }} </code>
                                        </div>
                                    @else
                                        <span class="text-base-content/40">—</span>
                                    @endif
                                </td>

                                {{-- Base URL --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Base-URL'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    @if($item->base_url)
                                        @php
                                            $host = parse_url($item->base_url, PHP_URL_HOST);
                                        @endphp

                                        <a href="{{ $item->base_url }}" target="_blank" class="link link-primary text-sm truncate max-w-[60%] md:max-w-none"
                                            rel="noopener noreferrer">
                                            {{ $host ?: $item->base_url }}
                                        </a>

                                    @else
                                        <span class="text-base-content/40">—</span>
                                    @endif
                                </td>

                                {{-- Job URL --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Job-URL'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    @if($item->job_url)
                                        @php
                                            $jobHost = parse_url($item->job_url, PHP_URL_HOST);
                                        @endphp

                                        <a href="{{ $item->job_url }}" target="_blank" class="link link-primary text-sm truncate max-w-[60%] md:max-w-none"
                                            rel="noopener noreferrer">
                                            {{ $jobHost ?: $item->job_url }}
                                        </a>

                                    @else
                                        <span class="text-base-content/40">—</span>
                                    @endif
                                </td>

                                {{-- Job Type --}}
                                <td class="flex md:table-cell items-center justify-between md:justify-start gap-2 px-0 md:px-4 py-1.5 md:py-3 before:content-['Type'] before:text-[10px] before:font-semibold before:uppercase before:text-slate-400 before:tracking-wide md:before:content-none md:before:hidden">
                                    @if($item->job_type)
                                        <span class="badge badge-ghost text-xs capitalize"> {{ str_replace('_', ' ', $item->job_type) }} </span>
                                    @else
                                        <span class="text-base-content/40">—</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="block md:table-cell px-0 md:px-4 py-2 md:py-3 border-t md:border-t-0 border-dashed border-slate-200 mt-2 md:mt-0 pt-2 md:pt-3">
                                    <div class="flex justify-stretch md:justify-end gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('platforms.edit', $item) }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-medium px-3 py-1.5 transition hover:text-indigo-600">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>

                                        {{-- Delete --}}
                                        <button type="button" class="delete-item flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 rounded-lg border border-rose-100 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium px-3 py-1.5 transition cursor-pointer"
                                            data-url="{{ route('platforms.destroy', $item) }}"
                                            data-name="{{ $item->name }}"
                                            data-title="Delete Platform">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="9">
                                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                                        <i class="fa-solid fa-layer-group text-4xl text-base-content/20 mb-3"></i>
                                        <h3 class="font-semibold">No platforms found</h3>
                                        <p class="text-sm text-base-content/50 mt-1">No platforms match your search.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div id="platformPagination" class="mt-8 pb-24 sm:pb-20 md:pb-0 overflow-x-auto" data-total="{{ $platforms->total() }}" data-per-page="{{ $platforms->perPage() }}">
        {{ $platforms->links() }}
    </div>
</div>

{{-- Mobile floating "Add Platform" button — hidden once the header button appears at sm+ --}}
<button onclick="platformModal.showModal()" class="sm:hidden fixed bottom-5 right-5 z-40 inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-900/30 hover:bg-indigo-700 cursor-pointer" aria-label="Add Platform">
    <i class="fa-solid fa-plus text-lg"></i>
</button>

{{-- DELETE CONFIRMATION MODAL --}}
<div>
    @include('modular.delete_modal')
</div>

{{-- ADD / EDIT Platform MODAL --}}
<dialog id="platformModal" class="modal">
    <div class="modal-box w-[95vw] sm:w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl">
        {{-- Modal Header --}}
        <h3 class="font-bold text-lg sm:text-xl mb-5">
            <i class="fa-solid fa-globe text-indigo-600 mr-2"></i>
            {{ isset($platform) ? 'Edit Platform' : 'Add Platform' }}
        </h3>

        <form action="{{ isset($platform) ? route('platforms.update', $platform) : route('platforms.store') }}" method="POST">
            @csrf

            @isset($platform)
                @method('PUT')
            @endisset

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Name --}}
                <div>
                    <label class="text-sm font-medium">Name</label>
                    <input type="text" name="name" class="input input-bordered w-full" placeholder="e.g. LinkedIn" value="{{ old('name',$platform->name ?? '') }}" required>
                </div>

                {{-- Icon --}}
                <div>
                    <label class="text-sm font-medium">Icon</label>
                    <input type="text" name="icon" class="input input-bordered w-full" placeholder="e.g. fa-brands fa-linkedin" value="{{ old('icon',$platform->icon ?? '') }}">
                    <p class="text-xs text-base-content/50 mt-1">Store the Font Awesome icon class.</p>
                </div>

                {{-- Color --}}
                <div>
                    <label class="text-sm font-medium">Brand Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="platformColor" value="#000000" class="w-12 h-10 p-1 rounded-lg border border-base-300 cursor-pointer shrink-0">
                        <input type="text" id="platformColorValue" name="color" value="{{ old('color',$platform->color ?? '#000000') }}" class="input input-bordered flex-1 min-w-0" placeholder="#0A66C2" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$">
                    </div>
                    <p class="text-xs text-base-content/50 mt-1">Use a 6-digit hexadecimal color such as #0A66C2.</p>
                </div>

                {{-- Base URL --}}
                <div>
                    <label class="text-sm font-medium">Base URL</label>
                    <input type="url" id="platformBaseUrl" name="base_url" value="{{ old('base_url',$platform->base_url ?? '') }}" class="input input-bordered w-full" placeholder="https://www.linkedin.com">
                    <p class="text-xs text-base-content/50 mt-1">The main URL of the platform.</p>
                </div>

                {{-- Job URL --}}
                <div>
                    <label class="text-sm font-medium">Job URL</label>
                    <input type="url" id="platformJobUrl" name="job_url" value="{{ old('job_url',$platform->job_url ?? '') }}" class="input input-bordered w-full" placeholder="https://www.linkedin.com/jobs">
                    <p class="text-xs text-base-content/50 mt-1">The platform's job listing or job search URL.</p>
                </div>

                {{-- Job Type --}}
                <div>
                    <label class="text-sm font-medium">Job Type</label>
                    <select name="job_type" class="select select-bordered w-full">
                        <option value="">Select job type</option>
                        <option value="onsite"{{ old('job_type',$platform->job_type ?? '') === 'onsite' ? 'selected' : '' }}>Onsite</option>
                        <option value="remote"{{ old('job_type',$platform->job_type ?? '') === 'remote' ? 'selected' : '' }}>Remote</option>
                        <option value="both"{{ old('job_type',$platform->job_type ?? '') === 'both' ? 'selected' : '' }}>Both</option>
                    </select>
                    <p class="text-xs text-base-content/50 mt-1">The type of job platform.</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="modal-action flex flex-col sm:flex-row justify-end items-stretch sm:items-end gap-3 sm:gap-4">
                <button type="submit" class="w-full sm:w-auto sm:flex-none inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 shadow-sm transition cursor-pointer whitespace-nowrap order-1 sm:order-2">
                    <i class="fa-solid {{ isset($platform) ? 'fa-check' : 'fa-plus' }}"></i> {{ isset($platform) ? 'Update' : 'Add Platform' }}
                </button>

                <a href="{{ route('platforms.index') }}" class="w-full sm:w-auto sm:flex-none inline-flex items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-medium px-4 py-2.5 transition whitespace-nowrap order-2 sm:order-1">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Close modal by clicking outside --}}
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

{{-- Open Edit / Validation Modal --}}
@if(isset($platform))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            platformModal.showModal();
        });
    </script>
@endif

@endsection