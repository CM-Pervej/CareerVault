@php
    $technology = $technology ?? null;
@endphp

<div class="space-y-6">

    {{-- Basic Information --}}
    <section class="cv-section">
        <div class="mb-5">
            <h2 class="text-base font-semibold text-base-content">Basic Information</h2>
            <p class="mt-1 text-sm text-base-content/60">
                Define the technology name, category, and description.
            </p>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div class="form-control md:col-span-2">
                <label for="name" class="label">
                    <span class="label-text font-medium">Technology Name <span class="text-error">*</span></span>
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $technology?->name) }}"
                    class="input input-bordered w-full @error('name') input-error @enderror"
                    placeholder="e.g. Laravel"
                    required
                    autofocus
                >

                @error('name')
                    <span class="mt-1 text-sm text-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="category" class="label">
                    <span class="label-text font-medium">Category</span>
                </label>

                <input
                    id="category"
                    type="text"
                    name="category"
                    value="{{ old('category', $technology?->category) }}"
                    class="input input-bordered w-full @error('category') input-error @enderror"
                    placeholder="e.g. Framework"
                >

                @error('category')
                    <span class="mt-1 text-sm text-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="sort_order" class="label">
                    <span class="label-text font-medium">Sort Order <span class="text-error">*</span></span>
                </label>

                <input
                    id="sort_order"
                    type="number"
                    name="sort_order"
                    value="{{ old('sort_order', $technology?->sort_order ?? 0) }}"
                    min="0"
                    class="input input-bordered w-full @error('sort_order') input-error @enderror"
                    required
                >

                @error('sort_order')
                    <span class="mt-1 text-sm text-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control md:col-span-2">
                <label for="description" class="label">
                    <span class="label-text font-medium">Description</span>
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="textarea textarea-bordered w-full @error('description') textarea-error @enderror"
                    placeholder="Briefly describe this technology..."
                >{{ old('description', $technology?->description) }}</textarea>

                @error('description')
                    <span class="mt-1 text-sm text-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </section>

    {{-- Appearance --}}
    <section class="cv-section">
        <div class="mb-5">
            <h2 class="text-base font-semibold text-base-content">Appearance</h2>
            <p class="mt-1 text-sm text-base-content/60">
                Configure the icon and brand color used throughout the application.
            </p>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div class="form-control">
                <label for="icon" class="label">
                    <span class="label-text font-medium">Icon</span>
                </label>

                <input
                    id="icon"
                    type="text"
                    name="icon"
                    value="{{ old('icon', $technology?->icon) }}"
                    class="input input-bordered w-full @error('icon') input-error @enderror"
                    placeholder="fa-brands fa-laravel"
                >

                <span class="mt-1 text-xs text-base-content/50">
                    Use a Font Awesome class.
                </span>

                @error('icon')
                    <span class="mt-1 text-sm text-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-control">
                <label for="color" class="label">
                    <span class="label-text font-medium">Color</span>
                </label>

                <div class="flex gap-2">
                    <input
                        id="color"
                        type="text"
                        name="color"
                        value="{{ old('color', $technology?->color) }}"
                        class="input input-bordered w-full @error('color') input-error @enderror"
                        placeholder="#FF2D20"
                    >
                </div>

                @error('color')
                    <span class="mt-1 text-sm text-error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </section>

    {{-- Status --}}
    <section class="cv-section">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-semibold text-base-content">Status</h2>
                <p class="mt-1 text-sm text-base-content/60">
                    Inactive technologies will remain available but won't be treated as active.
                </p>
            </div>

            <label class="cursor-pointer">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="toggle toggle-primary"
                    @checked(old('is_active', $technology?->is_active ?? true))
                >
            </label>
        </div>

        @error('is_active')
            <span class="mt-2 block text-sm text-error">{{ $message }}</span>
        @enderror
    </section>

</div>