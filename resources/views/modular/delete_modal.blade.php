<dialog id="deleteModal" class="modal">
    <div class="modal-box">
        <div class="flex items-center gap-3 mb-1">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 id="deleteModalTitle" class="font-bold text-lg">Delete</h3>
        </div>

        <p class="py-4">
            Are you sure you want to delete <span id="deleteItemName" class="font-bold"></span>? This action cannot be undone.
        </p>

        <div class="modal-action">
            <form method="dialog">
                <button type="submit" class="btn">Cancel</button>
            </form>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-error">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
</dialog>