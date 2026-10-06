@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">

        <div>
            <div class="text-sm opacity-50">
                {{ $country->name }}
            </div>

            <h1 class="text-2xl font-bold mt-1">
                State / Division Trash
            </h1>

            <p class="text-sm opacity-60 mt-1">
                Restore or permanently delete removed states and divisions.
            </p>
        </div>

        <a
            href="{{ route('admin.countries.states.index', $country) }}"
            class="btn btn-ghost"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Back to States
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
                    <th>State / Division</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Deleted At</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($states as $state)

                    <tr class="hover">

                        <td>
                            <div class="font-semibold">
                                {{ $state->name }}
                            </div>

                            <div class="text-xs opacity-50">
                                {{ $state->slug }}
                            </div>
                        </td>

                        <td>
                            {{ $state->code ?: '—' }}
                        </td>

                        <td>
                            {{ $state->type ?: '—' }}
                        </td>

                        <td>
                            {{ $state->deleted_at?->format('d M Y, h:i A') }}
                        </td>

                        <td>

                            <div class="flex justify-end gap-2">

                                <form
                                    method="POST"
                                    action="{{ route('admin.countries.states.restore', [$country, $state]) }}"
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
                                    action="{{ route('admin.countries.states.force-delete', [$country, $state]) }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-sm btn-error btn-outline"
                                        onclick="return confirm('Permanently delete this state / division? This cannot be undone.')"
                                    >
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-12">

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
        {{ $states->links() }}
    </div>

</div>
@endsection