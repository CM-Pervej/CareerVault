{{-- ========================= ACTIVE INDUSTRY — DANGER ZONE ========================= --}}
@if(!$industry->trashed())
    <section class="overflow-hidden rounded-sm border border-error/20 bg-error/5">
        <div class="flex flex-col gap-4 p-5 sm:p-6">
            <div class="flex flex-col min-w-0 items-start gap-3">
                <div class="flex justify-start items-center gap-2">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </span>

                    <span class="font-bold text-error text-xl">Delete this industry</span>
                </div>

                <div class="min-w-0">
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-base-content/60 text-justify">Move this industry to the trash. It will no longer be part of the active directory, but it can be restored later.</p>
                </div>
            </div>

            <div class="flex shrink-0 flex-wrap gap-2">
                <button
                    type="button"
                    class="btn btn-error btn-outline shrink-0 gap-2"
                    title="Move industry to trash"
                    data-admin-action
                    data-action-url="{{ route('admin.industries.destroy', $industry) }}"
                    data-action-method="DELETE"
                    data-action-type="danger"
                    data-action-title="Move Industry to Trash"
                    data-action-description="Move “{{ $industry->name }}” to trash. You can restore it later from the Trash page."
                    data-action-icon="fa-solid fa-trash-can"
                    data-action-confirm-icon="fa-solid fa-trash-can"
                    data-action-confirm-text="Move to Trash"
                >
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Move to Trash</span>
                </button>
            </div>
        </div>
    </section>
@endif

{{-- ========================== TRASH WARNING ============================ --}}
@if($industry->trashed())
    <section class="overflow-hidden rounded-sm border border-warning/30 bg-warning/10">
        <div class="flex flex-col gap-5 p-5 sm:p-6">
            <div class="flex flex-col gap-4 sm:items-start sm:justify-between">
                <div class="flex flex-col min-w-0 items-start gap-3">
                    <div class="flex justify-start items-center gap-2">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-warning/15 text-warning">
                            <i class="fa-solid fa-trash-can"></i>
                        </span>

                        <span class="font-bold text-warning text-xl">This industry is in Trash</span>
                    </div>

                    <div>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-base-content/65 text-justify">This industry is no longer part of the active directory, but the record is still available to authorized administrators.</p>

                        @if($industry->deleted_at)
                            <p class="mt-2 text-xs text-base-content/50">
                                Deleted {{ $industry->deleted_at->format('M d, Y \a\t H:i') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex shrink-0 flex-wrap gap-2">
                    <button
                        type="button"
                        class="btn btn-success gap-2"
                        title="Restore industry"
                        data-admin-action
                        data-action-url="{{ route('admin.industries.restore', $industry->slug) }}"
                        data-action-method="PATCH"
                        data-action-type="success"
                        data-action-title="Restore Industry"
                        data-action-description="Restore “{{ $industry->name }}” and return it to the active industries."
                        data-action-icon="fa-solid fa-rotate-left"
                        data-action-confirm-icon="fa-solid fa-rotate-left"
                        data-action-confirm-text="Restore Industry"
                    >
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Restore</span>
                    </button>

                    <button
                        type="button"
                        class="btn btn-error gap-2"
                        title="Delete permanently"
                        data-admin-action
                        data-action-url="{{ route('admin.industries.force-delete', $industry->slug) }}"
                        data-action-method="DELETE"
                        data-action-type="danger"
                        data-action-title="Delete Industry Permanently"
                        data-action-description="Permanently delete “{{ $industry->name }}”. This action cannot be undone."
                        data-action-icon="fa-solid fa-triangle-exclamation"
                        data-action-confirm-icon="fa-solid fa-trash-can"
                        data-action-confirm-text="Delete Forever"
                    >
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Delete Forever</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
@endif