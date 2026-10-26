<section class="sm:rounded-r-sm border border-base-300 bg-base-100 shadow-sm px-4 sm:px-6 py-5">
    <div class="border-b border-base-200 pb-3">
        <h2 class="text-xl font-bold pl-3 border-l-4" style="border-left-color: {{ $platform->color ?: 'currentColor' }}">
            About {{ $platform->name }}
        </h2>
    </div>

    <div class="pt-3 pb-4">
        @if(filled($platform->description))
            <div class="whitespace-pre-line text-sm leading-6 text-base-content/70 text-justify">{{ $platform->description }}</div>
        @else
            <p class="text-sm text-base-content/45">No detailed description has been added.</p>
        @endif
    </div>

    @php
        $foundedAt = null;
        $yearsInOperation = null;

        if ($platform->founded_year) {
            $foundedYear = (int) $platform->founded_year;
            $foundedMonth = $platform->founded_month
                ? (int) $platform->founded_month
                : 1;

            if ($foundedMonth >= 1 && $foundedMonth <= 12) {
                $foundedDate = \Carbon\Carbon::create(
                    $foundedYear,
                    $foundedMonth,
                    1
                )->startOfDay();

                $today = now()->startOfDay();

                if ($foundedDate->lte($today)) {
                    $foundedAt = $platform->founded_month
                        ? $foundedDate->format('F, Y')
                        : (string) $foundedYear;

                    $yearsInOperation = (int) $foundedDate->diffInYears($today);
                }
            }
        }

        $lastVerifiedAt = $platform->last_verified_at
            ? \Illuminate\Support\Carbon::parse($platform->last_verified_at)
                ->format('d M, Y - h:i A')
            : null;

        $rows = [
            ['Name', $platform->name],
            ['Official Name', $platform->official_name ?: '—'],
            ['URL', $platform->base_url ?: null],
            ['Career', $platform->job_url ?: null],
            ['Status', $platform->trashed()
                ? 'Trashed'
                : ($platform->is_active ? 'Active' : 'Inactive')],
            ['Job Type', $platform->job_type ?: '—'],
            ['Business Model', $platform->business_model ?: '—'],
            ['Account', $platform->account_required ? 'Required' : 'Not Required'],
            ['Bangladesh Focused', $platform->is_bangladesh_focused ? 'Yes' : 'Global'],
            ['Founded At', $foundedAt ?: '—'],
            ['Years in Operation', $yearsInOperation !== null
                ? $yearsInOperation . ' ' . ($yearsInOperation === 1 ? 'year' : 'years')
                : '—'],
            ['Founded By', $platform->founded_by ?: '—'],
            ['Last Verified', $lastVerifiedAt ?: '—'],
        ];
    @endphp

    <div class="divide-y divide-base-200">
        @foreach($rows as [$label, $value])
            <div class="row-hover flex {{ $label === 'Founded By' ? 'flex-col sm:flex-row items-start' : 'items-center justify-between' }} gap-2 sm:gap-6 py-1.5">
                <span class="text-sm text-base-content/50 shrink-0">{{ $label }}</span>

                <div class="{{ $label === 'Founded By' ? 'w-full' : 'min-w-0 sm:text-right' }}">
                    @if($label === 'Name')
                        <span class="text-xl sm:text-2xl font-bold break-words" style="color: {{ $platform->color ?: 'currentColor' }}"> {{ $value }} </span>

                    @elseif($label === 'Bangladesh Focused')
                        <span class="inline-flex rounded-md px-2.5 py-1 text-sm font-semibold text-white" style="background-color: {{ $platform->color ?: '#64748b' }}"> {{ $value }} </span>

                    @elseif($label === 'URL' || $label === 'Career')
                        @if(filled($value) && filter_var($value, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true))
                            <a href="{{ $value }}" target="_blank" rel="noopener noreferrer" class="inline-block max-w-full break-all text-sm font-semibold hover:underline" style="color: {{ $platform->color ?: 'currentColor' }}" title="{{ $value }}">
                                {{ $value }}
                            </a>
                        @else
                            <span class="text-sm font-semibold text-base-content/60 break-words"> {{ $value ?: '—' }} </span>
                        @endif

                    @elseif($label === 'Status')
                        @if($platform->trashed())
                            <span class="badge badge-warning font-semibold">Trashed</span>
                        @elseif($platform->is_active)
                            <span class="badge badge-success font-semibold">Active</span>
                        @else
                            <span class="badge badge-error font-semibold">Inactive</span>
                        @endif

                    @elseif($label === 'Founded By' && filled($value) && $value !== '—')
                        <div class="flex flex-wrap gap-2 sm:justify-end">
                            @foreach(array_filter(array_map('trim', explode(',', $value))) as $founder)
                                <span class="inline-flex max-w-full items-center rounded-md border border-base-300 bg-base-200/50 px-2.5 py-1 text-sm font-medium break-words">{{ $founder }}</span>
                            @endforeach
                        </div>

                    @else
                        <span class="text-sm font-semibold text-base-content break-words">
                            {{ $value }}
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- History --}}
<section class="sm:pl-6">
    <div class="bg-base-100">
        <div class="border-b border-base-300 sm:border-gray-300 sm:py-4">
            <div class="flex items-center justify-center gap-2 sm:justify-start">
                <i class="fa-solid fa-circle-info text-primary"></i>
                <h2 class="font-bold">History</h2>
            </div>
        </div>

        @php
            $history = [
                [
                    'label' => 'Status', 'value' => $platform->trashed() ? 'Trashed' : ($platform->is_active ? 'Active' : 'Inactive'), 'cl' => $platform->trashed() ? 'badge badge-warning badge-outline font-semibold' : ($platform->is_active ? 'badge badge-success badge-outline font-semibold' : 'badge badge-ghost font-semibold'),
                ], [
                    'label' => 'Created At', 'value' => $platform->created_at->format('M d, Y - H:i A'), 'cl' => 'font-semibold text-blue-700',
                ], [
                    'label' => 'Updated At', 'value' => $platform->updated_at?->format('M d, Y - H:i A') ?? 'Unknown', 'cl' => 'text-green-700 font-semibold',
                ], 
                ...($platform->trashed() ? [
                    [
                        'label' => 'Deleted At', 'value' => $platform->deleted_at?->format('M d, Y - H:i A') ?? 'Unknown', 'cl' => 'text-warning font-semibold',
                    ], 
                ] : []),
            ];
            $historyRight = [
                [
                    'label' => 'Last Verified', 'value' => $platform->last_verified_at ? $platform->last_verified_at->format('M d, Y - H:i A') : 'Not verified', 'cl' => 'font-semibold',
                ], [
                    'label' => 'Created By', 'value' => $platform->createdBy?->name ?? 'Unknown User', 'subvalue' => $platform->createdBy ? str_replace('_', ' ', $platform->createdBy->role) : null, 'cl' => 'font-black text-blue-700',
                ], [
                    'label' => 'Updated By', 'value' => $platform->updatedBy?->name ?? 'Unknown User', 'cl' => 'text-green-700 font-black', 'subvalue' => $platform->updatedBy ? str_replace('_', ' ', $platform->updatedBy->role) : null,
                ],
                ...($platform->trashed() ? [
                    [
                        'label' => 'Deleted By', 'value' => $platform->deletedBy?->name ?? 'Unknown User', 'cl' => 'text-warning font-black', 'subvalue' => $platform->deletedBy ? str_replace('_', ' ', $platform->deletedBy->role) : null,
                    ],
                ] : []),
            ];
        @endphp

        <div class="flex flex-col sm:flex-row justify-between sm:gap-5 py-2 sm:py-5">
            <div class="flex-1 w-full sm:border sm:border-gray-300 p-5 grid grid-cols-1 gap-2">
                @foreach($history as $item)
                    <div class="info-row flex items-center justify-between gap-4">
                        <span class="text-sm text-base-content/60"> {{ $item['label'] }} </span>
    
                        <div class="min-w-0 text-right">
                            <div class="text-sm {{ $item['cl'] ?? '' }}"> {{ $item['value'] }} </div>
    
                            @if(!empty($item['subvalue']))
                                <div class="mt-0.5 text-[9px] font-semibold uppercase tracking-wider text-blue-500"> {{ $item['subvalue'] }} </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="border border-gray-300"></div>
            <div class="flex-1 w-full sm:border sm:border-gray-300 p-5 grid grid-cols-1 gap-2">
                @foreach($historyRight as $item)
                    <div class="info-row flex items-center justify-between gap-4">
                        <span class="text-sm text-base-content/60"> {{ $item['label'] }} </span>
    
                        <div class="min-w-0 text-right">
                            <div class="text-sm {{ $item['cl'] ?? '' }}"> 
                                {{ $item['value'] }} 
                            
                                @if(!empty($item['subvalue']))
                                    <span class="mt-0.5 ml-1 text-[9px] font-bold tracking-wider uppercase
                                        {{ strtolower($item['subvalue']) === 'super admin' ? 'text-blue-600' : (strtolower($item['subvalue']) === 'admin' ? 'text-green-600' : (strtolower($item['subvalue']) === 'user' ? 'text-red-600' : 'text-black')) }}">
                                        ({{ $item['subvalue'] }})
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


{{-- ================= Active Page Danger Zone ==================== --}}
@if(!$platform->trashed())
    <section class="border border-error/20 bg-error/5 p-5">
        <div class="flex flex-col gap-1">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-error"></i>
                    <h2 class="font-bold text-error">Delete this page</h2>
                </div>

                <div>
                    @can('delete', $platform)
                        <button
                            type="button"
                            class="btn btn-error btn-outline btn-sm gap-2"
                            title="Move platform to trash"
                            data-admin-action
                            data-action-url="{{ route('admin.platforms.destroy', $platform->slug) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Move Platform to Trash"
                            data-action-description="Move “{{ $platform->name }}” to trash. You can restore it later from the Trash page."
                            data-action-icon="fa-solid fa-trash-can"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Move to Trash"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Move to Trash</span>
                        </button>
                    @endcan
                </div>
            </div>

            <p class="mt-1 text-sm text-base-content/60">The platform will be moved to trash. Its content can still be restored later.</p>
        </div>
    </section>
@endif

{{-- =================== Trash Warning ======================= --}}
@if($platform->trashed())
    <section class="border border-warning/30 bg-warning/10 p-5">
        <div class="flex flex-col gap-1">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-trash-can text-warning"></i>
                    <h2 class="font-bold text-warning">This platform is in Trash</h2>
                </div>
                <div class="flex shrink-0 flex-wrap gap-2">
                    @can('restore', $platform)
                        <button
                            type="button"
                            class="btn btn-success gap-2"
                            title="Restore platform"
                            data-admin-action
                            data-action-url="{{ route('admin.platforms.restore', $platform->slug) }}"
                            data-action-method="PATCH"
                            data-action-type="success"
                            data-action-title="Restore Platform"
                            data-action-description="Restore “{{ $platform->name }}” and return it to the active platforms."
                            data-action-icon="fa-solid fa-rotate-left"
                            data-action-confirm-icon="fa-solid fa-rotate-left"
                            data-action-confirm-text="Restore Platform"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                            <span class="hidden lg:inline">Restore</span>
                        </button>
                    @endcan

                    @can('forceDelete', $platform)
                        <button
                            type="button"
                            class="btn btn-error gap-2"
                            title="Delete permanently"
                            data-admin-action
                            data-action-url="{{ route('admin.platforms.force-delete', $platform->slug) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Delete Platform Permanently"
                            data-action-description="Permanently delete “{{ $platform->name }}”. This action cannot be undone."
                            data-action-icon="fa-solid fa-triangle-exclamation"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Delete Forever"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                            <span class="hidden lg:inline whitespace-nowrap">Delete Forever</span>
                        </button>
                    @endcan
                </div>
            </div>

            <p class="mt-1 text-justify text-sm leading-6 text-base-content/65">This page is no longer part of the active platform directory, but its content is still available to authorized administrators.</p>

            @if($platform->deleted_at)
                <p class="mt-1 text-xs text-base-content/50">Deleted {{ $platform->deleted_at->format('M d, Y \a\t H:i') }} </p>
            @endif
        </div>
    </section>
@endif