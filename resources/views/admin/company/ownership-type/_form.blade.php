
@csrf

<div class="space-y-5">
    <section class="rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-4 py-3 sm:px-5">
            <h2 class="font-semibold text-base-content">Basic information</h2>
            <p class="mt-1 text-sm text-base-content/60">Define the ownership type and its purpose.</p>
        </div>

        <div class="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
            <div>
                <label for="name" class="mb-1.5 block text-sm font-medium">
                    Name <span class="text-error">*</span>
                </label>
                <input id="name" name="name" type="text"
                    value="{{ old('name', $ownershipType->name ?? '') }}"
                    class="input input-bordered w-full @error('name') input-error @enderror"
                    required maxlength="255">
                @error('name')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="mb-1.5 block text-sm font-medium">Slug</label>
                <input id="slug" name="slug" type="text"
                    value="{{ old('slug', $ownershipType->slug ?? '') }}"
                    class="input input-bordered w-full @error('slug') input-error @enderror"
                    maxlength="255" placeholder="Generated from name when blank">
                @error('slug')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="mb-1.5 block text-sm font-medium">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="textarea textarea-bordered w-full @error('description') textarea-error @enderror"
                    placeholder="Describe this ownership structure...">{{ old('description', $ownershipType->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <section class="rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-4 py-3 sm:px-5">
            <h2 class="font-semibold text-base-content">Display and status</h2>
            <p class="mt-1 text-sm text-base-content/60">Control availability and ordering.</p>
        </div>

        <div class="grid gap-4 p-4 sm:grid-cols-2 sm:p-5">
            <div>
                <label for="sort_order" class="mb-1.5 block text-sm font-medium">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0"
                    value="{{ old('sort_order', $ownershipType->sort_order ?? 0) }}"
                    class="input input-bordered w-full @error('sort_order') input-error @enderror">
                @error('sort_order')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex cursor-pointer items-center justify-between gap-4 rounded-lg border border-base-300 p-3">
                <span>
                    <span class="block text-sm font-medium">Active status</span>
                    <span class="mt-0.5 block text-xs text-base-content/60">
                        Inactive types can be hidden from selection lists.
                    </span>
                </span>
                <input type="checkbox" name="is_active" value="1"
                    class="toggle toggle-primary"
                    @checked(old('is_active', $ownershipType->is_active ?? true))>
            </label>
        </div>
    </section>

    <div class="sticky bottom-0 z-10 flex flex-col-reverse gap-3 border-t border-base-300 bg-base-100/95 py-3 backdrop-blur sm:flex-row sm:justify-end">
        <a href="{{ route('admin.ownership-types.index') }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" class="btn btn-primary">
            {{ isset($ownershipType) ? 'Save changes' : 'Create ownership type' }}
        </button>
    </div>
</div>
