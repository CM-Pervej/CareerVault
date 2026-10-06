@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('admin.cities.index') }}">Cities</a></li>
                    <li>Trash</li>
                </ul>
            </div>

            <h1 class="mt-1 text-2xl font-bold">City Trash</h1>
            <p class="text-sm text-base-content/60">
                Restore or permanently remove deleted cities.
            </p>
        </div>

        <a href="{{ route('admin.cities.index') }}" class="btn btn-ghost btn-sm">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Cities
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="hidden overflow-x-auto rounded-box border border-base-300 bg-base-100 md:block">
        <table class="table">
            <thead>
                <tr>
                    <th>City</th>
                    <th>Country</th>
                    <th>State / Division</th>
                    <th>Deleted</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($cities as $city)
                    <tr>
                        <td>
                            <div class="font-semibold">{{ $city->name }}</div>
                            <div class="text-xs text-base-content/50">
                                {{ $city->slug }}
                            </div>
                        </td>

                        <td>{{ $city->country?->name ?? '—' }}</td>

                        <td>{{ $city->state?->name ?? '—' }}</td>

                        <td class="text-sm">
                            {{ $city->deleted_at?->format('d M Y, h:i A') }}
                        </td>

                        <td>
                            <div class="flex justify-end gap-1">
                                <form method="POST"
                                      action="{{ route('admin.cities.restore', $city) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-ghost btn-xs text-success"
                                            title="Restore">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.cities.force-delete', $city) }}"
                                      onsubmit="return confirm('Permanently delete this city? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-ghost btn-xs text-error"
                                            title="Delete permanently">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <i class="fa-solid fa-trash-can mb-3 text-3xl text-base-content/30"></i>
                            <div class="font-semibold">Trash is empty</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid gap-3 md:hidden">
        @forelse($cities as $city)
            <div class="card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-4">

                    <div>
                        <h3 class="font-bold">{{ $city->name }}</h3>
                        <p class="text-xs text-base-content/50">
                            {{ $city->country?->name ?? '—' }}
                            @if($city->state)
                                · {{ $city->state->name }}
                            @endif
                        </p>
                    </div>

                    <div class="mt-3 flex justify-end gap-1">
                        <form method="POST"
                              action="{{ route('admin.cities.restore', $city) }}">
                            @csrf
                            @method('PATCH')

                            <button class="btn btn-success btn-outline btn-xs">
                                <i class="fa-solid fa-rotate-left"></i>
                                Restore
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('admin.cities.force-delete', $city) }}"
                              onsubmit="return confirm('Permanently delete this city? This cannot be undone.')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-error btn-outline btn-xs">
                                <i class="fa-solid fa-trash-can"></i>
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="rounded-box border border-base-300 p-10 text-center">
                <i class="fa-solid fa-trash-can mb-3 text-3xl text-base-content/30"></i>
                <div class="font-semibold">Trash is empty</div>
            </div>
        @endforelse
    </div>

    @if($cities->hasPages())
        <div>
            {{ $cities->links() }}
        </div>
    @endif

</div>
@endsection