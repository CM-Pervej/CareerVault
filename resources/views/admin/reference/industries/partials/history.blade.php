{{-- ====================== HISTORY ============================ --}}
<section class="mt-5 overflow-hidden rounded-sm border border-base-300 bg-base-100 shadow-sm">
    <div class="border-b border-base-300 px-5 py-4 sm:px-6">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10 text-primary">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>

            <div>
                <h2 class="font-bold">History</h2>
                <p class="text-xs text-base-content/50">Lifecycle and record metadata</p>
            </div>
        </div>
    </div>

    @php
        $history = [
            [
                'label' => 'Status',
                'value' => $industry->trashed()
                    ? 'Trashed'
                    : ($industry->is_active ? 'Active' : 'Inactive'),
                'cl' => $industry->trashed()
                    ? 'badge badge-warning badge-outline font-semibold'
                    : ($industry->is_active
                        ? 'badge badge-success badge-outline font-semibold'
                        : 'badge badge-ghost font-semibold'),
            ],
            [
                'label' => 'Created At',
                'value' => $industry->created_at?->format('M d, Y - h:i A') ?? 'Unknown',
                'cl' => 'font-semibold',
            ],
            [
                'label' => 'Created By', 'value' => $industry->createdBy?->name ?? 'Unknown User', 'subvalue' => $industry->createdBy ? str_replace('_', ' ', $industry->createdBy->role) : null, 'cl' => 'font-black text-blue-700',
            ], 
            [
                'label' => 'Updated At',
                'value' => $industry->updated_at?->format('M d, Y - h:i A') ?? 'Unknown',
                'cl' => 'font-semibold',
            ],
            [
                'label' => 'Updated By', 'value' => $industry->updatedBy?->name ?? 'Unknown User', 'cl' => 'text-green-700 font-black', 'subvalue' => $industry->updatedBy ? str_replace('_', ' ', $industry->updatedBy->role) : null,
            ],
            ...($industry->trashed() ? [
                [
                    'label' => 'Deleted At',
                    'value' => $industry->deleted_at?->format('M d, Y - h:i A') ?? 'Unknown',
                    'cl' => 'font-semibold text-warning',
                ],
                [
                    'label' => 'Deleted By',
                    'value' => $industry->deletedBy?->name ?? 'Unknown User',
                    'cl' => 'font-semibold text-warning',
                ],
            ] : []),
        ];
    @endphp

    @props(['part' => null,])

    <div>
        <div class="divide-y divide-base-300 grid {{ $part }}">
            @foreach($history as $item)
                <div class="flex justify-between items-center gap-5 px-5 py-4 sm:px-6">
                    <span class="shrink-0 text-sm text-base-content/55">
                        {{ $item['label'] }}
                    </span>

                    <div class="min-w-0 text-right">
                        <div class="truncate text-sm {{ $item['cl'] ?? '' }}">
                            {{ $item['value'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>