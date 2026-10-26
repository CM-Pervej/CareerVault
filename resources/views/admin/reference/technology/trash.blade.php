@extends('layouts.admin.app')

@section('title', 'Technology Trash | CareerVault')
@section('page_title', 'Technologies / Trash')

@section('content')
<div class="space-y-4 p-4 sm:p-6">

    <x-admin.ui.page-header
        title="Technology Trash"
        description="Restore technologies or permanently remove them from CareerVault."
        icon="fa-solid fa-trash-can"
        title-color="text-error"
        title-bg-color="bg-error/10 text-error"
    >
        <x-slot:actions>
            <a href="{{ route('admin.technology.index') }}" class="btn btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Technologies
            </a>
        </x-slot:actions>
    </x-admin.ui.page-header>
    
    <a href="{{ route('admin.technology.index') }}" class="btn btn-ghost">
    <i class="fa-solid fa-arrow-left"></i>
    Back to Technologies
</a>

    {{-- Trash Count --}}
    <div class="grid grid-cols-1 sm:grid-cols-2">
        <x-admin.ui.statistic
            title="Items in Trash"
            :value="$trashedTechnologies"
            icon="fa-solid fa-trash-can"
        />
    </div>

    {{-- Filters --}}
    <div class="rounded-lg border border-base-300 bg-base-100 shadow-sm">
        <form method="GET" action="{{ route('admin.technology.trash') }}">
            <div class="grid gap-4 p-4 md:grid-cols-3">

                <div class="form-control">
                    <label for="search" class="label">
                        <span class="label-text font-medium">Search</span>
                    </label>

                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="input input-bordered w-full"
                        placeholder="Search technologies..."
                    >
                </div>

                <div class="form-control">
                    <label for="category" class="label">
                        <span class="label-text font-medium">Category</span>
                    </label>

                    <select id="category" name="category" class="select select-bordered w-full">
                        <option value="">All Categories</option>

                        @foreach($categories as $item)
                            <option value="{{ $item }}" @selected($category === $item)>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end justify-end gap-2">
                    <a href="{{ route('admin.technology.trash') }}" class="btn btn-ghost">
                        Reset
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-lg border border-base-300 bg-base-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Technology</th>
                        <th>Category</th>
                        <th>Deleted By</th>
                        <th>Deleted At</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($technologies as $technology)
                        <tr class="hover">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-white opacity-70"
                                        style="background-color: {{ $technology->color ?: '#6366F1' }}"
                                    >
                                        <i class="{{ $technology->icon ?: 'fa-solid fa-code' }}"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="font-semibold">{{ $technology->name }}</p>
                                        <p class="text-xs text-base-content/50">
                                            {{ $technology->slug }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($technology->category)
                                    <span class="badge badge-ghost">
                                        {{ $technology->category }}
                                    </span>
                                @else
                                    <span class="text-base-content/40">—</span>
                                @endif
                            </td>

                            <td>
                                {{ $technology->deleter?->name ?? 'Unknown' }}
                            </td>

                            <td>
                                <div class="text-sm">
                                    {{ $technology->deleted_at?->format('M d, Y') }}
                                </div>

                                <div class="text-xs text-base-content/50">
                                    {{ $technology->deleted_at?->format('H:i') }}
                                </div>
                            </td>

                            <td>
                                <div class="flex justify-end gap-1">
                                    <form
                                        method="POST"
                                        action="{{ route('admin.technology.restore', $technology->id) }}"
                                        onsubmit="return confirm('Restore this technology?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-ghost btn-sm text-success" title="Restore">
                                            <i class="fa-solid fa-trash-arrow-up"></i>
                                        </button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.technology.force-delete', $technology->id) }}"
                                        onsubmit="return confirm('Permanently delete this technology? This action cannot be undone.')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-ghost btn-sm text-error" title="Permanently Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fa-solid fa-trash-can text-3xl text-base-content/20"></i>

                                    <div>
                                        <p class="font-medium">Trash is empty.</p>
                                        <p class="text-sm text-base-content/50">
                                            Deleted technologies will appear here.
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($technologies->hasPages())
            <div class="border-t border-base-300 px-4 py-3">
                {{ $technologies->links() }}
            </div>
        @endif
    </div>

</div>
@endsection