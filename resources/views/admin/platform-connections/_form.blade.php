@php
    $isEdit = isset($platform);

    $existingConnections = $isEdit
        ? $platform->connectedPlatforms
        : collect();

    $oldConnections = old('connections');

    if($oldConnections !== null){
        $formConnections = collect($oldConnections);
    }elseif($existingConnections->isNotEmpty()){
        $formConnections = $existingConnections->map(function($connectedPlatform){
            return [
                'connected_platform_id' => $connectedPlatform->id,
                'account_url' => $connectedPlatform->pivot->account_url,
            ];
        });
    }else{
        $formConnections = collect([
            [
                'connected_platform_id' => '',
                'account_url' => '',
            ]
        ]);
    }
@endphp

<form
    method="POST"
    action="{{ $isEdit
        ? route('admin.platform-connections.update', $platform)
        : route('admin.platform-connections.store') }}"
    class="space-y-6"
>
    @csrf

    @if($isEdit)
        @method('PUT')
    @endif

    <input
        type="hidden"
        name="platform_id"
        value="{{ $platform->id ?? '' }}"
    >

    {{-- Main Platform --}}
    <div class="card border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body">

            <div>
                <h2 class="text-lg font-black">
                    Main Platform
                </h2>

                <p class="mt-1 text-sm text-base-content/60">
                    Select the platform whose official presence you are managing.
                </p>
            </div>

            @if($isEdit)

                <div class="flex items-center gap-4 rounded-xl border border-base-300 bg-base-200/40 p-4">
                    <div class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-base-300 bg-base-100">
                        @if($platform->logo)
                            <img
                                src="{{ Storage::url($platform->logo) }}"
                                alt="{{ $platform->name }}"
                                class="size-full object-contain"
                            >
                        @else
                            <span class="text-xl font-black">
                                {{ Str::upper(Str::substr($platform->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <div class="text-lg font-black">
                            {{ $platform->name }}
                        </div>

                        <div class="text-sm text-base-content/50">
                            {{ $platform->slug }}
                        </div>
                    </div>
                </div>

            @else

                <select
                    name="platform_id"
                    id="platform_id"
                    class="select select-bordered w-full @error('platform_id') select-error @enderror"
                    required
                >
                    <option value="">Select platform</option>

                    @foreach($platforms as $item)
                        <option
                            value="{{ $item->id }}"
                            @selected(old('platform_id') == $item->id)
                        >
                            {{ $item->name }}
                            @unless($item->is_active)
                                — Inactive
                            @endunless
                        </option>
                    @endforeach
                </select>

                @error('platform_id')
                    <div class="mt-1 text-sm text-error">
                        {{ $message }}
                    </div>
                @enderror

            @endif

        </div>
    </div>

    {{-- Connections --}}
    <div class="card border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-lg font-black">
                        Official Presence
                    </h2>

                    <p class="mt-1 text-sm text-base-content/60">
                        Add all official accounts or profiles for this platform at once.
                    </p>
                </div>

                <button
                    type="button"
                    id="add-connection"
                    class="btn btn-sm btn-outline"
                >
                    + Add Platform
                </button>
            </div>

            <div
                id="connections-container"
                class="space-y-3"
            >

                @foreach($formConnections as $index => $connection)

                    <div
                        class="connection-row rounded-xl border border-base-300 bg-base-200/30 p-4"
                        data-index="{{ $index }}"
                    >
                        <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.5fr)_auto] lg:items-end">

                            {{-- Connected platform --}}
                            <div class="form-control">
                                <label class="label pt-0">
                                    <span class="label-text text-xs font-bold uppercase tracking-wide">
                                        Platform
                                    </span>
                                </label>

                                <select
                                    name="connections[{{ $index }}][connected_platform_id]"
                                    class="select select-bordered w-full connection-platform"
                                    required
                                >
                                    <option value="">
                                        Select platform
                                    </option>

                                    @foreach($platforms as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            @selected(
                                                ($connection['connected_platform_id'] ?? '') == $item->id
                                            )
                                        >
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error("connections.$index.connected_platform_id")
                                    <div class="mt-1 text-sm text-error">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Account URL --}}
                            <div class="form-control">
                                <label class="label pt-0">
                                    <span class="label-text text-xs font-bold uppercase tracking-wide">
                                        Official Account / Profile URL
                                    </span>
                                </label>

                                <input
                                    type="url"
                                    name="connections[{{ $index }}][account_url]"
                                    value="{{ $connection['account_url'] ?? '' }}"
                                    placeholder="https://www.linkedin.com/company/example/"
                                    class="input input-bordered w-full"
                                >

                                @error("connections.$index.account_url")
                                    <div class="mt-1 text-sm text-error">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Remove --}}
                            <button
                                type="button"
                                class="btn btn-square btn-ghost text-error remove-connection"
                                title="Remove connection"
                            >
                                ×
                            </button>

                        </div>
                    </div>

                @endforeach

            </div>

            {{-- Empty state --}}
            <div
                id="empty-connections"
                class="{{ $formConnections->isNotEmpty() ? 'hidden' : '' }} rounded-xl border border-dashed border-base-300 py-10 text-center"
            >
                <div class="text-sm font-bold">
                    No connected platforms
                </div>

                <p class="mt-1 text-xs text-base-content/50">
                    Add an official platform presence above.
                </p>
            </div>

        </div>
    </div>

    {{-- Explanation --}}
    <div class="alert border border-info/20 bg-info/5">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="size-5 shrink-0"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"
            />
        </svg>

        <div class="text-sm">
            <div class="font-bold">
                How this works
            </div>

            <div class="mt-1 text-base-content/60">
                Each row represents an official presence of this platform on another platform.
                You can add or update all of them together.
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap items-center justify-end gap-3">
        <a
            href="{{ route('admin.platform-connections.index') }}"
            class="btn btn-ghost"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="btn btn-primary"
        >
            {{ $isEdit ? 'Save Connections' : 'Create Connections' }}
        </button>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('connections-container');
    const emptyState = document.getElementById('empty-connections');
    const addButton = document.getElementById('add-connection');

    let nextIndex = {{ $formConnections->count() }};

    const refreshEmptyState = () => {
        const rows = container.querySelectorAll('.connection-row');

        emptyState.classList.toggle('hidden', rows.length > 0);
    };

    const updatePlatformOptions = () => {
        const selectedValues = [...container.querySelectorAll('.connection-platform')]
            .map(select => select.value)
            .filter(Boolean);

        container.querySelectorAll('.connection-platform').forEach(select => {
            [...select.options].forEach(option => {
                if(!option.value){
                    return;
                }

                option.disabled =
                    selectedValues.includes(option.value) &&
                    option.value !== select.value;
            });
        });
    };

    const addConnection = () => {
        const index = nextIndex++;

        const row = document.createElement('div');

        row.className =
            'connection-row rounded-xl border border-base-300 bg-base-200/30 p-4';

        row.dataset.index = index;

        row.innerHTML = `
            <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.5fr)_auto] lg:items-end">

                <div class="form-control">
                    <label class="label pt-0">
                        <span class="label-text text-xs font-bold uppercase tracking-wide">
                            Platform
                        </span>
                    </label>

                    <select
                        name="connections[${index}][connected_platform_id]"
                        class="select select-bordered w-full connection-platform"
                        required
                    >
                        <option value="">Select platform</option>

                        @foreach($platforms as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label pt-0">
                        <span class="label-text text-xs font-bold uppercase tracking-wide">
                            Official Account / Profile URL
                        </span>
                    </label>

                    <input
                        type="url"
                        name="connections[${index}][account_url]"
                        placeholder="https://www.linkedin.com/company/example/"
                        class="input input-bordered w-full"
                    >
                </div>

                <button
                    type="button"
                    class="btn btn-square btn-ghost text-error remove-connection"
                    title="Remove connection"
                >
                    ×
                </button>

            </div>
        `;

        container.appendChild(row);

        refreshEmptyState();
        updatePlatformOptions();
    };

    addButton.addEventListener('click', addConnection);

    container.addEventListener('click', event => {
        const removeButton = event.target.closest('.remove-connection');

        if(!removeButton){
            return;
        }

        removeButton.closest('.connection-row').remove();

        refreshEmptyState();
        updatePlatformOptions();
    });

    container.addEventListener('change', event => {
        if(event.target.classList.contains('connection-platform')){
            updatePlatformOptions();
        }
    });

    refreshEmptyState();
    updatePlatformOptions();
});
</script>
@endpush