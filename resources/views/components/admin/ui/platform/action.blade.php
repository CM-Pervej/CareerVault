@props([
    'viewUrl',
    'editUrl',
    'viewTitle' => 'View',
    'itemName' => 'item',
    'itemTitle' => null,
    'parentUrl' => null,
    'parentLabel' => 'View platform',
    'deleteUrl' => null,
    'deleteTitle' => 'Move item to Trash',
    'deleteDescription' => null,
    'canDelete' => false,
])

{{-- ======================= Desktop ACTIONS ======================= --}}
<div class="hidden sm:flex shrink-0 items-center gap-2 xl:w-36 xl:justify-end">
    <a href="{{ $viewUrl }}" class="btn btn-sm btn-outline gap-2">
        <i class="fa-solid fa-eye"></i> {{ $viewTitle }}
    </a>

    <div class="dropdown dropdown-center dropdown-left">
        <button type="button" tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="{{ ucfirst($itemName) }} actions">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
            <li>
                <a href="{{ $viewUrl }}">
                    <i class="fa-solid fa-eye"></i> View {{ $itemName }}
                </a>
            </li>

            <li>
                <a href="{{ $editUrl }}">
                    <i class="fa-solid fa-pen-to-square"></i> Edit {{ $itemName }}
                </a>
            </li>

            @if($parentUrl)
                <li>
                    <a href="{{ $parentUrl }}">
                        <i class="fa-solid fa-layer-group"></i> {{ $parentLabel }}
                    </a>
                </li>
            @endif

            @if($canDelete && $deleteUrl)
                <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                    <span>Danger zone</span>
                </li>

                <li>
                    <button
                        type="button"
                        class="flex items-center gap-2 text-error hover:bg-error/10"
                        title="{{ $deleteTitle }}"
                        data-admin-action
                        data-action-url="{{ $deleteUrl }}"
                        data-action-method="DELETE"
                        data-action-type="danger"
                        data-action-title="{{ $deleteTitle }}"
                        data-action-description="{{ $deleteDescription ?? 'Are you sure you want to continue?' }}"
                        data-action-icon="fa-solid fa-trash-can"
                        data-action-confirm-icon="fa-solid fa-trash-can"
                        data-action-confirm-text="Move to Trash"
                    >
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Move to Trash</span>
                    </button>
                </li>
            @endif
        </ul>
    </div>
</div>

{{-- ======================= Mobile ACTIONS ======================= --}}
<div class="sm:hidden flex items-center justify-between">
    <a href="{{ $viewUrl }}" class="btn btn-sm btn-outline">
        <i class="fa-solid fa-eye"></i> View
    </a>

    <a href="{{ $editUrl }}" class="btn btn-sm btn-outline">
        <i class="fa-solid fa-pen-to-square"></i> Edit
    </a>

    @if($parentUrl)
        <a href="{{ $parentUrl }}" class="btn btn-sm btn-outline">
            <i class="fa-solid fa-layer-group"></i> Platform
        </a>
    @endif

    @if($canDelete && $deleteUrl)
        <button
            type="button"
            class="text-error flex items-center hover:bg-error/10 btn btn-sm btn-outline border-error"
            title="{{ $deleteTitle }}"
            data-admin-action
            data-action-url="{{ $deleteUrl }}"
            data-action-method="DELETE"
            data-action-type="danger"
            data-action-title="{{ $deleteTitle }}"
            data-action-description="{{ $deleteDescription ?? 'Are you sure you want to continue?' }}"
            data-action-icon="fa-solid fa-trash-can"
            data-action-confirm-icon="fa-solid fa-trash-can"
            data-action-confirm-text="Move to Trash"
        >
            <i class="fa-solid fa-trash-can"></i> Trash
        </button>
    @endif
</div>