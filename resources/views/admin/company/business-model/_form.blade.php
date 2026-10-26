@csrf

<div class="space-y-6">
    <section class="rounded-xl border border-base-300 bg-base-100 p-4 sm:p-6">
        <h2 class="text-base font-semibold">Business Model Information</h2>
        <p class="mt-1 text-sm text-base-content/60">Enter the business model's basic details.</p>

        <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="name" class="mb-1.5 block text-sm font-medium">Name <span class="text-error">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $businessModel?->name) }}" required maxlength="255" class="input input-bordered w-full @error('name') input-error @enderror" placeholder="e.g. Subscription-Based">
                @error('name')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="description" class="mb-1.5 block text-sm font-medium">Description</label>
                <textarea id="description" name="description" rows="4" class="textarea textarea-bordered w-full @error('description') textarea-error @enderror" placeholder="Describe this business model...">{{ old('description', $businessModel?->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sort_order" class="mb-1.5 block text-sm font-medium">Sort Order <span class="text-error">*</span></label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $businessModel?->sort_order ?? 0) }}" required class="input input-bordered w-full @error('sort_order') input-error @enderror">
                @error('sort_order')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <label for="is_active" class="flex cursor-pointer items-center gap-3">
                    <input id="is_active" name="is_active" type="checkbox" value="1" class="checkbox checkbox-primary" @checked(old('is_active', $businessModel?->is_active ?? true))>
                    <span>
                        <span class="block text-sm font-medium">Active</span>
                        <span class="block text-xs text-base-content/60">Make this business model available for use.</span>
                    </span>
                </label>
            </div>
        </div>
    </section>

    <div class="sticky bottom-0 z-10 flex flex-wrap items-center justify-end gap-3 border-t border-base-300 bg-base-100/95 py-4 backdrop-blur">
        <a href="{{ route('admin.business-models.index') }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary">
            {{ isset($businessModel) && $businessModel->exists ? 'Update Business Model' : 'Create Business Model' }}
        </button>
    </div>
</div>