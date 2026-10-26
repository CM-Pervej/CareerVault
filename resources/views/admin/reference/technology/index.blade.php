@extends('layouts.admin.app')

@section('title', 'Technologies | CareerVault')
@section('page_title', 'Technologies')

@section('content')
<div class="space-y-4 p-4 sm:p-6">

    <x-admin.ui.page-header
        title="Technologies"
        description="Manage technologies used across CareerVault."
        icon="fa-solid fa-code"
    >
        <x-slot:actions>
            <a href="{{ route('admin.technology.trash') }}" class="btn btn-error btn-outline">
                <i class="fa-solid fa-trash-can"></i>
                Trash
                @if($trashedTechnologies > 0)
                    <span class="badge badge-error">{{ $trashedTechnologies }}</span>
                @endif
            </a>

            <a href="{{ route('admin.technology.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Add Technology
            </a>
        </x-slot:actions>
    </x-admin.ui.page-header>
    
    <a href="{{ route('admin.technology.trash') }}" class="btn btn-error btn-outline">
    <i class="fa-solid fa-trash-can"></i>
    Trash

    @if($trashedTechnologies > 0)
        <span class="badge badge-error">{{ $trashedTechnologies }}</span>
    @endif
</a>

    {{-- Statistics --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <x-admin.ui.statistic
            title="Total"
            :value="$totalTechnologies"
            icon="fa-solid fa-code"
        />

        <x-admin.ui.statistic
            title="Active"
            :value="$activeTechnologies"
            icon="fa-solid fa-circle-check"
        />

        <x-admin.ui.statistic
            title="Inactive"
            :value="$inactiveTechnologies"
            icon="fa-solid fa-circle-pause"
        />

        <x-admin.ui.statistic
            title="Trash"
            :value="$trashedTechnologies"
            icon="fa-solid fa-trash-can"
        />
    </div>

    {{-- Filters --}}
    <div class="rounded-lg border border-base-300 bg-base-100 shadow-sm">
        <form method="GET" action="{{ route('admin.technology.index') }}">
            <div class="grid gap-4 p-4 sm:grid-cols-2 lg:grid-cols-4">

                <div class="form-control lg:col-span-2">
                    <label for="search" class="label">
                        <span class="label-text font-medium">Search</span>
                    </label>

                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        class="input input-bordered w-full"
                        placeholder="Search name, slug, category..."
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

                <div class="form-control">
                    <label for="status" class="label">
                        <span class="label-text font-medium">Status</span>
                    </label>

                    <select id="status" name="status" class="select select-bordered w-full">
                        <option value="">All Statuses</option>
                        <option value="active" @selected($status === 'active')>Active</option>
                        <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-base-300 px-4 py-3">
                <a href="{{ route('admin.technology.index') }}" class="btn btn-ghost btn-sm">
                    Reset
                </a>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>
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
                        <th>Status</th>
                        <th>Sort</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($technologies as $technology)
                        <tr class="hover">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-white"
                                        style="background-color: {{ $technology->color ?: '#6366F1' }}"
                                    >
                                        <i class="{{ $technology->icon ?: 'fa-solid fa-code' }}"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <a
                                            href="{{ route('admin.technology.show', $technology) }}"
                                            class="font-semibold hover:text-primary"
                                        >
                                            {{ $technology->name }}
                                        </a>

                                        <div class="text-xs text-base-content/50">
                                            {{ $technology->slug }}
                                        </div>
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
                                @if($technology->is_active)
                                    <span class="badge badge-success badge-soft">Active</span>
                                @else
                                    <span class="badge badge-warning badge-soft">Inactive</span>
                                @endif
                            </td>

                            <td>{{ $technology->sort_order }}</td>

                            <td>
                                <div class="flex justify-end gap-1">
                                    <a
                                        href="{{ route('admin.technology.show', $technology) }}"
                                        class="btn btn-ghost btn-sm"
                                        title="View"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a
                                        href="{{ route('admin.technology.edit', $technology) }}"
                                        class="btn btn-ghost btn-sm"
                                        title="Edit"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.technology.destroy', $technology) }}"
                                        onsubmit="return confirm('Move this technology to trash?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-ghost btn-sm text-error" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fa-solid fa-code text-3xl text-base-content/20"></i>
                                    <div>
                                        <p class="font-medium">No technologies found.</p>
                                        <p class="text-sm text-base-content/50">
                                            Try changing your filters or add a new technology.
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