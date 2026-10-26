@props([
    'title',
    'subtitle' => null,
    'status' => null,
    'statusType' => 'success',
    'icon' => null,
])

<div class="card bg-base-100 border border-base-300 shadow-sm">
    <div class="card-body p-4">

        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0 flex items-start gap-3">

                @if($icon)
                    <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <i class="{{ $icon }}"></i>
                    </div>
                @endif

                <div class="min-w-0">
                    <h2 class="font-bold text-lg truncate"> {{ $title }} </h2>

                    @if($subtitle)
                        <p class="text-sm text-base-content/60 truncate"> {{ $subtitle }} </p>
                    @endif
                </div>
            </div>

            @if($status !== null)
                <span class="badge badge-{{ $statusType }} badge-sm shrink-0"> {{ $status }} </span>
            @endif
        </div>

        @if(isset($details))
            <div class="grid grid-cols-2 gap-3 text-sm mt-3"> {{ $details }} </div>
        @endif

        @if(isset($actions))
            <div class="flex justify-end gap-1 pt-3 border-t border-base-300 mt-3"> {{ $actions }} </div>
        @endif
    </div>
</div>