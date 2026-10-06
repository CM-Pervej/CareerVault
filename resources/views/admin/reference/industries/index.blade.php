@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Industries</h1>
            <p class="text-sm opacity-60 mt-1">
                Manage industries and their sub-industries.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.industries.trash') }}" class="btn btn-ghost">
                <i class="fa-solid fa-trash-can"></i>
                Trash
            </a>

            <a href="{{ route('admin.industries.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                Add Industry
            </a>
        </div>
    </div>

    <form method="GET" class="card bg-base-100 border border-base-300 shadow-sm mb-5">
        <div class="card-body p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search industries..."
                    class="input input-bordered w-full"
                >

                <select name="parent" class="select select-bordered w-full">
                    <option value="">All parent industries</option>

                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" @selected(request('parent') == $parent->id)>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="select select-bordered w-full">
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('status') === '1')>Active</option>
                    <option value="0" @selected(request('status') === '0')>Inactive</option>
                </select>

                <div class="flex gap-2">
                    <button class="btn btn-primary flex-1">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    <a href="{{ route('admin.industries.index') }}" class="btn btn-ghost">
                        Reset
                    </a>
                </div>

            </div>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success mb-5">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-5">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="hidden md:block overflow-x-auto bg-base-100 border border-base-300 rounded-2xl shadow-sm">
        <table class="table">
            <thead>
                <tr>
                    <th>Industry</th>
                    <th>Parent</th>
                    <th>Children</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($industries as $industry)
                    <tr class="hover">
                        <td>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl flex items-center justify-center bg-base-200"
                                    @if($industry->color)
                                        style="color: {{ $industry->color }}"
                                    @endif
                                >
                                    <i class="{{ $industry->icon ?: 'fa-solid fa-layer-group' }}"></i>
                                </div>

                                <div>
                                    <a
                                        href="{{ route('admin.industries.show', $industry) }}"
                                        class="font-semibold hover:text-primary"
                                    >
                                        {{ $industry->name }}
                                    </a>

                                    <div class="text-xs opacity-50">
                                        {{ $industry->slug }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($industry->parent)
                                <span class="badge badge-ghost">
                                    {{ $industry->parent->name }}
                                </span>
                            @else
                                <span class="text-xs opacity-50">
                                    Top-level
                                </span>
                            @endif
                        </td>

                        <td>
                            <span class="badge badge-outline">
                                {{ $industry->children_count }}
                            </span>
                        </td>

                        <td>
                            @if($industry->is_active)
                                <span class="badge badge-success badge-sm">Active</span>
                            @else
                                <span class="badge badge-ghost badge-sm">Inactive</span>
                            @endif
                        </td>

                        <td>{{ $industry->sort_order }}</td>

                        <td>
                            <div class="flex justify-end gap-1">
                                <a
                                    href="{{ route('admin.industries.show', $industry) }}"
                                    class="btn btn-ghost btn-sm"
                                    title="View"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a
                                    href="{{ route('admin.industries.edit', $industry) }}"
                                    class="btn btn-ghost btn-sm"
                                    title="Edit"
                                >
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.industries.destroy', $industry) }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-ghost btn-sm text-error"
                                        title="Move to trash"
                                        onclick="return confirm('Move this industry to trash?')"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="opacity-50">
                                <i class="fa-solid fa-layer-group text-3xl mb-3"></i>
                                <p>No industries found.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden space-y-3">
        @forelse($industries as $industry)
            <div class="card bg-base-100 border border-base-300 shadow-sm">
                <div class="card-body p-4">

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-11 h-11 shrink-0 rounded-xl flex items-center justify-center bg-base-200"
                                @if($industry->color)
                                    style="color: {{ $industry->color }}"
                                @endif
                            >
                                <i class="{{ $industry->icon ?: 'fa-solid fa-layer-group' }}"></i>
                            </div>

                            <div class="min-w-0">
                                <a
                                    href="{{ route('admin.industries.show', $industry) }}"
                                    class="font-bold hover:text-primary"
                                >
                                    {{ $industry->name }}
                                </a>

                                <div class="text-xs opacity-50 truncate">
                                    {{ $industry->slug }}
                                </div>
                            </div>
                        </div>

                        @if($industry->is_active)
                            <span class="badge badge-success badge-sm">Active</span>
                        @else
                            <span class="badge badge-ghost badge-sm">Inactive</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-3 text-sm">
                        <div class="bg-base-200 rounded-lg p-3">
                            <div class="text-xs opacity-50">Parent</div>
                            <div class="font-medium mt-1">
                                {{ $industry->parent?->name ?? 'Top-level' }}
                            </div>
                        </div>

                        <div class="bg-base-200 rounded-lg p-3">
                            <div class="text-xs opacity-50">Children</div>
                            <div class="font-medium mt-1">
                                {{ $industry->children_count }}
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-1 mt-3">
                        <a
                            href="{{ route('admin.industries.show', $industry) }}"
                            class="btn btn-sm btn-ghost"
                        >
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a
                            href="{{ route('admin.industries.edit', $industry) }}"
                            class="btn btn-sm btn-ghost"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form method="POST" action="{{ route('admin.industries.destroy', $industry) }}">
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-sm btn-ghost text-error"
                                onclick="return confirm('Move this industry to trash?')"
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body text-center py-12 opacity-50">
                    <i class="fa-solid fa-layer-group text-3xl mb-3"></i>
                    <p>No industries found.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5">
        {{ $industries->links() }}
    </div>

</div>
@endsection