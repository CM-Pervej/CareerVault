@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold">Industry Trash</h1>
            <p class="text-sm opacity-60 mt-1">
                Restore or permanently remove deleted industries.
            </p>
        </div>

        <a href="{{ route('admin.industries.index') }}" class="btn btn-ghost">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Industries
        </a>
    </div>

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

    <div class="overflow-x-auto bg-base-100 border border-base-300 rounded-2xl shadow-sm">
        <table class="table">
            <thead>
                <tr>
                    <th>Industry</th>
                    <th>Parent</th>
                    <th>Deleted At</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($industries as $industry)
                    <tr class="hover">
                        <td>
                            <div class="font-semibold">{{ $industry->name }}</div>
                            <div class="text-xs opacity-50">{{ $industry->slug }}</div>
                        </td>

                        <td>
                            {{ $industry->parent?->name ?? 'Top-level' }}
                        </td>

                        <td>
                            <span class="text-sm">
                                {{ $industry->deleted_at?->format('d M Y, h:i A') }}
                            </span>
                        </td>

                        <td>
                            <div class="flex justify-end gap-2">

                                <form
                                    method="POST"
                                    action="{{ route('admin.industries.restore', $industry) }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-sm btn-success btn-outline">
                                        <i class="fa-solid fa-rotate-left"></i>
                                        Restore
                                    </button>
                                </form>

                                <form
                                    method="POST"
                                    action="{{ route('admin.industries.force-delete', $industry) }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-sm btn-error btn-outline"
                                        onclick="return confirm('Permanently delete this industry? This cannot be undone.')"
                                    >
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-12">
                            <div class="opacity-50">
                                <i class="fa-solid fa-trash-can text-3xl mb-3"></i>
                                <p>Trash is empty.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $industries->links() }}
    </div>

</div>
@endsection