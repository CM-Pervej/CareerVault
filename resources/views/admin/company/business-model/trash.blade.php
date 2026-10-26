@extends('layouts.admin.app')

@section('title', 'Business Models Trash | CareerVault')
@section('page_title', 'Business Models / Trash')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-error">Business Models Trash</h1>
            <p class="mt-1 text-sm text-base-content/60">Restore removed business models or permanently delete them.</p>
        </div>

        <a href="{{ route('admin.business-models.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Business Models
        </a>
    </div>

    <div class="rounded-xl border border-error/20 bg-error/5 p-4">
        <p class="text-sm">
            <i class="fa-solid fa-triangle-exclamation mr-1 text-error"></i>
            Permanently deleted records cannot be restored.
            <strong>{{ number_format($trashedBusinessModelsCount) }}</strong> record(s) currently in trash.
        </p>
    </div>

    <section class="rounded-xl border border-base-300 bg-base-100 p-4">
        <form method="GET" action="{{ route('admin.business-models.trash') }}" class="flex flex-col gap-3 sm:flex-row">
            <input type="search" name="search" value="{{ $search }}" class="input input-bordered input-sm w-full" placeholder="Search name, slug or description">
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                <a href="{{ route('admin.business-models.trash') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </form>
    </section>

    <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Deleted by</th>
                        <th>Deleted at</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($businessModels as $businessModel)
                        <tr>
                            <td>
                                <p class="font-semibold">{{ $businessModel->name }}</p>
                                @if ($businessModel->description)
                                    <p class="mt-1 max-w-sm truncate text-xs text-base-content/60">{{ $businessModel->description }}</p>
                                @endif
                            </td>
                            <td><code class="text-xs">{{ $businessModel->slug }}</code></td>
                            <td>{{ $businessModel->deleter?->name ?? 'Unknown' }}</td>
                            <td>{{ $businessModel->deleted_at?->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.business-models.restore', $businessModel->slug) }}" onsubmit="return confirm('Restore this business model?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-xs">
                                            <i class="fa-solid fa-rotate-left"></i> Restore
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.business-models.force-delete', $businessModel->slug) }}" onsubmit="return confirm('Permanently delete this business model? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-error btn-xs">
                                            <i class="fa-solid fa-trash"></i> Delete Forever
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <i class="fa-solid fa-trash-can mb-3 text-3xl text-base-content/30"></i>
                                <p class="font-medium">Trash is empty</p>
                                <p class="mt-1 text-sm text-base-content/60">Deleted business models will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($businessModels->hasPages())
            <div class="border-t border-base-300 p-4">
                {{ $businessModels->links() }}
            </div>
        @endif
    </section>
</div>
@endsection