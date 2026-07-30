<div class="card bg-base-100 shadow-xl border border-base-300/60 rounded-2xl">
    <div class="card-body p-6 lg:p-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4 text-center sm:text-left">
                <div class="hidden sm:flex w-12 h-12 rounded-xl bg-gradient-to-br from-success/15 to-success/5 items-center justify-center ring-1 ring-success/10 shrink-0">
                    <i class="fa-solid fa-circle-check text-success text-lg"></i>
                </div>

                <div>
                    <h3 class="font-semibold text-base-content text-lg">Ready to save?</h3>
                    <p class="text-sm text-base-content/60 mt-0.5">Review the information above before saving the company.</p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('companies.index') }}" class="btn btn-ghost">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Cancel
                </a>

                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    {{ isset($company) ? 'Update Company' : 'Save Company' }}
                </button>
            </div>
        </div>
    </div>
</div>