@php
    $isEdit = isset($platform);

    $existingConnections = $isEdit ? $platform->connectedPlatforms : collect();
    $oldConnections = old('connections');

    if ($oldConnections !== null) {
        $formConnections = collect($oldConnections);
    } elseif ($existingConnections->isNotEmpty()) {
        $formConnections = $existingConnections->map(function ($connectedPlatform) {
            return [
                'connected_platform_id' => $connectedPlatform->id,
                'account_url' => $connectedPlatform->pivot->account_url,
            ];
        });
    } else {
        $formConnections = collect([
            ['connected_platform_id' => '', 'account_url' => ''],
        ]);
    }

    // Prepare platform logo/icon metadata outside the JavaScript directive.
    // Keeping this expression out of @json avoids Blade's directive parser
    // misreading nested arrays and parentheses.
    $platformPreviewData = $platforms->map(function ($item) {
        return [
            'id' => (string) $item->id,
            'name' => $item->name,
            'logo' => $item->logo ? Storage::url($item->logo) : null,
            'icon' => $item->icon,
            'color' => $item->color,
        ];
    })->values();
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('admin.platform-connections.update', $platform) : route('admin.platform-connections.store') }}"
    class="space-y-6"
    id="platform-connections-form"
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    @if ($isEdit)
        <input type="hidden" name="platform_id" value="{{ $platform->id }}">
    @endif

    {{-- Main platform --}}
    <section class="overflow-hidden rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 bg-base-200/30 px-5 py-4 sm:px-6">
            <div class="flex items-start gap-3">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <h2 class="text-base font-extrabold tracking-tight sm:text-lg">Main platform</h2>
                    <p class="mt-1 text-sm leading-5 text-base-content/60">Choose the platform whose official presence you want to manage.</p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            @if ($isEdit)
                <div class="flex min-w-0 items-center gap-4 rounded-2xl border border-base-300 bg-base-200/20 p-4">
                    <div class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-base-300 bg-base-100 shadow-sm sm:size-16">
                        @if ($platform->logo)
                            <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-1.5">
                        @else
                            <span class="text-2xl font-black text-primary">{{ Str::upper(Str::substr($platform->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="truncate text-lg font-extrabold sm:text-xl">{{ $platform->name }}</div>
                        <div class="mt-1 truncate text-sm text-base-content/55">{{ $platform->slug }}</div>
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <span class="badge badge-sm {{ $platform->is_active ? 'badge-success badge-outline' : 'badge-ghost' }}">
                                {{ $platform->is_active ? 'Active platform' : 'Inactive platform' }}
                            </span>
                            <span class="text-xs text-base-content/50">Platform ID: {{ $platform->id }}</span>
                        </div>
                    </div>
                </div>
            @else
                <label for="platform_id" class="mb-2 block text-sm font-bold">Select main platform <span class="text-error">*</span></label>
                <select name="platform_id" id="platform_id" class="select select-bordered w-full @error('platform_id') select-error @enderror" required>
                    <option value="">Choose a platform…</option>
                    @foreach ($platforms as $item)
                        <option value="{{ $item->id }}" @selected(old('platform_id') == $item->id)>
                            {{ $item->name }}{{ ! $item->is_active ? ' — Inactive' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('platform_id')
                    <p class="mt-2 text-sm text-error">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-base-content/50">This is the platform that will appear as the source of each connection.</p>
            @endif
        </div>
    </section>

    {{-- Connected platforms --}}
    <section class="overflow-hidden rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="flex flex-col gap-4 border-b border-base-300 bg-base-200/30 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div class="flex min-w-0 items-start gap-3">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-secondary/10 text-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="6" cy="12" r="3"/><circle cx="18" cy="6" r="3"/><circle cx="18" cy="18" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="m8.7 10.7 6.6-3.4M8.7 13.3l6.6 3.4"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-base font-extrabold tracking-tight sm:text-lg">Official presence</h2>
                        <span id="connection-count" class="badge badge-ghost badge-sm">{{ $formConnections->count() }} {{ \Illuminate\Support\Str::plural('connection', $formConnections->count()) }}</span>
                    </div>
                    <p class="mt-1 text-sm leading-5 text-base-content/60">Link this platform to its verified pages or profiles on other platforms.</p>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6">
            <div id="connections-container" class="space-y-4">
                @foreach ($formConnections as $index => $connection)
                    <article class="connection-row relative rounded-2xl border border-base-300 bg-base-100 p-4 transition-colors hover:border-primary/40 sm:p-5" data-index="{{ $index }}">
                        <div class="mb-4 flex items-center justify-between gap-3 border-b border-base-200 pb-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-base-200 text-sm font-extrabold text-base-content/70 connection-number">{{ $loop->iteration }}</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold">Connection <span class="connection-label-number">{{ $loop->iteration }}</span></p>
                                    <p class="text-xs text-base-content/50">External official page or profile</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-ghost btn-sm remove-connection text-error hover:bg-error/10" title="Remove connection" aria-label="Remove connection">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                                    <path d="M4 7h16M10 11v6M14 11v6M5 7l1 14h12l1-14M9 7V4h6v3"/>
                                </svg>
                                <span class="hidden sm:inline">Remove</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <div class="form-control min-w-0">
                                <label for="connection-platform-{{ $index }}" class="mb-2 block text-sm font-bold">Connected platform <span class="text-error">*</span></label>
                                <div class="flex min-w-0 items-center gap-3">
                                    <div class="connection-platform-preview flex size-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-base-300 bg-base-200/50 text-base font-black text-base-content/60">
                                        <img class="connection-platform-logo hidden size-full object-contain bg-base-100 p-1.5" alt="">
                                        <i class="connection-platform-icon hidden" aria-hidden="true"></i>
                                        <span class="connection-platform-fallback">?</span>
                                    </div>
                                    <select id="connection-platform-{{ $index }}" name="connections[{{ $index }}][connected_platform_id]" class="select select-bordered min-w-0 flex-1 connection-platform @error("connections.$index.connected_platform_id") select-error @enderror" required>
                                    <option value="">Choose a platform…</option>
                                    @foreach ($platforms as $item)
                                        <option value="{{ $item->id }}" @selected(($connection['connected_platform_id'] ?? '') == $item->id)>
                                            {{ $item->name }}{{ ! $item->is_active ? ' — Inactive' : '' }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                                @error("connections.$index.connected_platform_id")
                                    <p class="mt-2 text-sm text-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-control min-w-0">
                                <label for="connection-url-{{ $index }}" class="mb-2 block text-sm font-bold">Official account / profile URL <span class="text-base-content/40 font-normal">(optional)</span></label>
                                <input id="connection-url-{{ $index }}" type="url" name="connections[{{ $index }}][account_url]" value="{{ $connection['account_url'] ?? '' }}" placeholder="https://example.com/your-official-page" class="input input-bordered w-full @error("connections.$index.account_url") input-error @enderror" inputmode="url" autocomplete="url">
                                @error("connections.$index.account_url")
                                    <p class="mt-2 text-sm text-error">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-xs text-base-content/50">Paste the direct URL to the official page or profile.</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div id="empty-connections" class="{{ $formConnections->isNotEmpty() ? 'hidden' : '' }} rounded-2xl border border-dashed border-base-300 px-5 py-10 text-center">
                <span class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-base-200 text-base-content/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <circle cx="6" cy="12" r="3"/><circle cx="18" cy="6" r="3"/><circle cx="18" cy="18" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="m8.7 10.7 6.6-3.4M8.7 13.3l6.6 3.4"/>
                    </svg>
                </span>
                <h3 class="mt-3 font-bold">No connections added yet</h3>
                <p class="mx-auto mt-1 max-w-sm text-sm text-base-content/60">Add an official page or profile to keep this platform’s presence organized in one place.</p>
                <button type="button" class="btn btn-outline btn-sm mt-4" id="empty-add-connection">Add your first connection</button>
            </div>

            <div class="mt-5 flex items-start gap-3 rounded-xl bg-info/5 p-4 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 size-5 shrink-0 text-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 7h.01"/>
                </svg>
                <p class="leading-6 text-base-content/65"><span class="font-bold text-base-content">Tip:</span> Add one row for each external platform where this platform has an official account. Duplicate platforms are disabled automatically to help prevent accidental duplicate connections.</p>
            </div>

            {{-- Bottom add-connection action --}}
            <div class="mt-5 rounded-2xl border border-base-300 bg-gradient-to-br from-primary/[0.07] via-base-100 to-secondary/[0.06] p-4 sm:p-5">
                <div class="flex flex-col items-stretch gap-4 sm:flex-row sm:items-center">
                    <div class="flex min-w-0 flex-1 items-center gap-3">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-primary text-primary-content shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="font-extrabold tracking-tight">Add another official presence</p>
                            <p class="mt-1 text-sm leading-5 text-base-content/60">Connect another platform page or profile to this directory.</p>
                        </div>
                    </div>
                    <button type="button" id="add-connection" class="btn btn-primary w-full gap-2 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Add connection
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Form actions --}}
    <div class="sticky bottom-3 z-20 rounded-2xl border border-base-300 bg-base-100/95 p-3 shadow-lg backdrop-blur sm:bottom-4 sm:p-4">
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="hidden text-sm text-base-content/50 md:block">Review your connections before saving.</p>
            <div class="grid grid-cols-2 gap-2 sm:flex sm:items-center">
                <a href="{{ route('admin.platform-connections.index') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary min-w-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 12 4 4L19 6"/>
                    </svg>
                    <span>{{ $isEdit ? 'Save changes' : 'Create connections' }}</span>
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('platform-connections-form');
        const container = document.getElementById('connections-container');
        const emptyState = document.getElementById('empty-connections');
        const addButton = document.getElementById('add-connection');
        const emptyAddButton = document.getElementById('empty-add-connection');
        const countBadge = document.getElementById('connection-count');
        const platformData = @json($platformPreviewData);
        let nextIndex = {{ $formConnections->count() }};

        if (!form || !container || !emptyState || !addButton) return;

        const platformById = new Map(platformData.map(platform => [String(platform.id), platform]));

        const renderPlatformPreview = (select) => {
            const row = select.closest('.connection-row');
            const preview = row?.querySelector('.connection-platform-preview');
            if (!preview) return;

            const image = preview.querySelector('.connection-platform-logo');
            const icon = preview.querySelector('.connection-platform-icon');
            const fallback = preview.querySelector('.connection-platform-fallback');
            const platform = platformById.get(String(select.value));

            image.classList.add('hidden');
            icon.classList.add('hidden');
            fallback.classList.add('hidden');

            if (platform?.logo) {
                image.src = platform.logo;
                image.alt = platform.name ? `${platform.name} logo` : 'Platform logo';
                image.classList.remove('hidden');
            } else {
                image.removeAttribute('src');
                image.alt = '';
                const iconClass = String(platform?.icon || '').trim();
                if (iconClass && /(^|\s)(fa|fab|fas|far|fa-brands|fa-solid|fa-regular)(\s|$)/.test(iconClass)) {
                    icon.className = `connection-platform-icon ${iconClass}`;
                    icon.classList.remove('hidden');
                } else {
                    fallback.textContent = iconClass || platform?.name?.trim()?.charAt(0)?.toUpperCase() || '?';
                    fallback.classList.remove('hidden');
                }
            }

            if (platform?.color) {
                preview.style.borderColor = `${platform.color}55`;
                preview.style.backgroundColor = `${platform.color}12`;
                if (!platform.logo) preview.style.color = platform.color;
            } else {
                preview.style.removeProperty('border-color');
                preview.style.removeProperty('background-color');
                preview.style.removeProperty('color');
            }
        };

        const refreshPlatformPreviews = () => {
            container.querySelectorAll('.connection-platform').forEach(renderPlatformPreview);
        };

        const refreshRows = () => {
            const rows = [...container.querySelectorAll('.connection-row')];
            emptyState.classList.toggle('hidden', rows.length !== 0);
            countBadge.textContent = `${rows.length} ${rows.length === 1 ? 'connection' : 'connections'}`;

            rows.forEach((row, position) => {
                const number = position + 1;
                row.querySelector('.connection-number').textContent = number;
                row.querySelector('.connection-label-number').textContent = number;
            });
        };

        const updatePlatformOptions = () => {
            const selects = [...container.querySelectorAll('.connection-platform')];
            const selectedValues = selects.map(select => select.value).filter(Boolean);

            selects.forEach(select => {
                [...select.options].forEach(option => {
                    if (!option.value) return;
                    option.disabled = selectedValues.includes(option.value) && option.value !== select.value;
                });
            });
        };

        const addConnection = () => {
            const index = nextIndex++;
            const row = document.createElement('article');
            row.className = 'connection-row relative rounded-2xl border border-base-300 bg-base-100 p-4 transition-colors hover:border-primary/40 sm:p-5';
            row.dataset.index = index;
            row.innerHTML = `
                <div class="mb-4 flex items-center justify-between gap-3 border-b border-base-200 pb-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="connection-number flex size-9 shrink-0 items-center justify-center rounded-xl bg-base-200 text-sm font-extrabold text-base-content/70"></span>
                        <div class="min-w-0">
                            <p class="text-sm font-bold">Connection <span class="connection-label-number"></span></p>
                            <p class="text-xs text-base-content/50">External official page or profile</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-ghost btn-sm remove-connection text-error hover:bg-error/10" title="Remove connection" aria-label="Remove connection">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                            <path d="M4 7h16M10 11v6M14 11v6M5 7l1 14h12l1-14M9 7V4h6v3"/>
                        </svg>
                        <span class="hidden sm:inline">Remove</span>
                    </button>
                </div>
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="form-control min-w-0">
                        <label for="connection-platform-${index}" class="mb-2 block text-sm font-bold">Connected platform <span class="text-error">*</span></label>
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="connection-platform-preview flex size-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-base-300 bg-base-200/50 text-base font-black text-base-content/60">
                                <img class="connection-platform-logo hidden size-full object-contain bg-base-100 p-1.5" alt="">
                                <i class="connection-platform-icon hidden" aria-hidden="true"></i>
                                <span class="connection-platform-fallback">?</span>
                            </div>
                            <select id="connection-platform-${index}" name="connections[${index}][connected_platform_id]" class="select select-bordered min-w-0 flex-1 connection-platform" required>
                            <option value="">Choose a platform…</option>
                            @foreach ($platforms as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}{{ ! $item->is_active ? ' — Inactive' : '' }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-control min-w-0">
                        <label for="connection-url-${index}" class="mb-2 block text-sm font-bold">Official account / profile URL <span class="text-base-content/40 font-normal">(optional)</span></label>
                        <input id="connection-url-${index}" type="url" name="connections[${index}][account_url]" placeholder="https://example.com/your-official-page" class="input input-bordered w-full" inputmode="url" autocomplete="url">
                        <p class="mt-2 text-xs text-base-content/50">Paste the direct URL to the official page or profile.</p>
                    </div>
                </div>
            `;

            container.appendChild(row);
            refreshRows();
            updatePlatformOptions();
            refreshPlatformPreviews();
            row.querySelector('select')?.focus();
            row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        };

        addButton.addEventListener('click', addConnection);
        emptyAddButton?.addEventListener('click', addConnection);

        container.addEventListener('click', event => {
            const removeButton = event.target.closest('.remove-connection');
            if (!removeButton) return;

            const row = removeButton.closest('.connection-row');
            if (row) row.remove();

            refreshRows();
            updatePlatformOptions();
        });

        container.addEventListener('change', event => {
            if (event.target.classList.contains('connection-platform')) {
                updatePlatformOptions();
                refreshPlatformPreviews();
            }
        });

        form.addEventListener('submit', event => {
            const rows = [...container.querySelectorAll('.connection-row')];
            const chosenPlatforms = rows.map(row => row.querySelector('.connection-platform')?.value).filter(Boolean);

            if (new Set(chosenPlatforms).size !== chosenPlatforms.length) {
                event.preventDefault();
                updatePlatformOptions();
                const duplicateSelect = rows.find((row, index) => chosenPlatforms.indexOf(row.querySelector('.connection-platform')?.value) !== index)?.querySelector('.connection-platform');
                duplicateSelect?.focus();
                duplicateSelect?.setCustomValidity('Choose a different platform for each connection.');
                duplicateSelect?.reportValidity();
                return;
            }

            rows.forEach(row => row.querySelector('.connection-platform')?.setCustomValidity(''));
        });

        container.addEventListener('change', event => {
            if (event.target.classList.contains('connection-platform')) {
                event.target.setCustomValidity('');
            }
        });

        refreshRows();
        updatePlatformOptions();
        refreshPlatformPreviews();
    });
</script>
@endpush
