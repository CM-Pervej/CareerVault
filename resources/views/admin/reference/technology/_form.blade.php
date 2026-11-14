
@php
    $technology = $technology ?? null;
    $isEdit = $technology !== null;
    $activeValue = old('is_active', $technology ? (int) $technology->is_active : 1);
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.technology.update', $technology) : route('admin.technology.store') }}" class="space-y-7">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- Basic Information --}}
    <section class="overflow-hidden">
        <div class="flex items-center gap-3 border-b border-base-300/80 px-5 sm:px-7">
            <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div class="min-w-0 flex-1">
                <h2 class="font-bold text-base-content">Basic Information</h2>
                <p class="mt-0.5 text-xs text-base-content/50 sm:text-sm">Establish the identity and classification of this technology.</p>
            </div>
            <span class="badge badge-ghost text-xs">01</span>
        </div>

        <div class="grid grid-cols-1 gap-6 p-5 sm:p-7">
            {{-- Technology Name --}}
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-base-content">
                    Technology Name <span class="text-error">*</span>
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    required
                    maxlength="255"
                    autofocus
                    value="{{ old('name', $technology?->name ?? '') }}"
                    placeholder="e.g. Laravel"
                    class="input input-bordered h-12 w-full bg-base-100 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('name') input-error @enderror"
                >
                @error('name')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1.5 text-xs text-base-content/45">Enter a clear and recognizable technology name.</p>
            </div>

            {{-- Technology Category --}}
            <div>
                <label for="category_id" class="mb-2 block text-sm font-semibold text-base-content">
                    Technology Category <span class="ml-1 font-normal text-base-content/40">(Optional)</span>
                </label>
                <select
                    id="category_id"
                    name="category_id"
                    class="select select-bordered h-12 w-full bg-base-100 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('category_id') select-error @enderror"
                >
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id', $technology?->category_id ?? '') === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1.5 text-xs text-base-content/45">Choose an existing technology category. Categories are managed separately.</p>
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="mb-2 block text-sm font-semibold text-base-content">
                    Description <span class="ml-1 font-normal text-base-content/40">(Optional)</span>
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Briefly describe this technology..."
                    class="textarea textarea-bordered min-h-28 w-full bg-base-100 leading-6 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('description') textarea-error @enderror"
                >{{ old('description', $technology?->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Appearance --}}
    <section class="overflow-hidden">
        <div class="flex items-center gap-3 border-b border-base-300/80 px-5 sm:px-7">
            <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-secondary/10 text-secondary">
                <i class="fa-solid fa-palette"></i>
            </div>
            <div class="min-w-0 flex-1">
                <h2 class="font-bold text-base-content">Appearance</h2>
                <p class="mt-0.5 text-xs text-base-content/50 sm:text-sm">Configure the icon and brand color used throughout the application.</p>
            </div>
            <span class="badge badge-ghost text-xs">02</span>
        </div>

        <div class="grid grid-cols-1 gap-6 p-5 sm:grid-cols-2 sm:p-7">
            {{-- Icon --}}
            <div>
                <label for="icon" class="mb-2 block text-sm font-semibold text-base-content">
                    Font Awesome Icon <span class="ml-1 font-normal text-base-content/40">(Optional)</span>
                </label>
                <input
                    id="icon"
                    name="icon"
                    type="text"
                    maxlength="255"
                    value="{{ old('icon', $technology?->icon ?? '') }}"
                    placeholder="e.g. fa-brands fa-laravel"
                    class="input input-bordered h-12 w-full bg-base-100 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('icon') input-error @enderror"
                >
                @error('icon')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1.5 text-xs text-base-content/45">Enter the Font Awesome icon classes.</p>
            </div>

            {{-- Color --}}
            <div>
                <label for="color" class="mb-2 block text-sm font-semibold text-base-content">
                    Brand Color <span class="ml-1 font-normal text-base-content/40">(Optional)</span>
                </label>
                <input
                    id="color"
                    name="color"
                    type="text"
                    maxlength="30"
                    value="{{ old('color', $technology?->color ?? '') }}"
                    placeholder="e.g. #FF2D20"
                    class="input input-bordered h-12 w-full bg-base-100 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('color') input-error @enderror"
                >
                @error('color')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1.5 text-xs text-base-content/45">Use a valid hexadecimal color code or supported color value.</p>
            </div>
        </div>
    </section>

    {{-- Settings --}}
    <section class="overflow-hidden">
        <div class="flex items-center gap-3 border-b border-base-300/80 px-5 sm:px-7">
            <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-accent/10 text-accent">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <div class="min-w-0 flex-1">
                <h2 class="font-bold text-base-content">Settings & Visibility</h2>
                <p class="mt-0.5 text-xs text-base-content/50 sm:text-sm">Control how this technology is organized and displayed.</p>
            </div>
            <span class="badge badge-ghost text-xs">03</span>
        </div>

        <div class="grid grid-cols-1 gap-6 p-5 sm:grid-cols-2 sm:p-7">
            {{-- Sort Order --}}
            <div>
                <label for="sort_order" class="mb-2 block text-sm font-semibold text-base-content">
                    Sort Order <span class="text-error">*</span>
                </label>
                <input
                    id="sort_order"
                    name="sort_order"
                    type="number"
                    min="0"
                    required
                    value="{{ old('sort_order', $technology?->sort_order ?? 0) }}"
                    class="input input-bordered h-12 w-full bg-base-100 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('sort_order') input-error @enderror"
                >
                @error('sort_order')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1.5 text-xs text-base-content/45">Lower values appear earlier in the ordering.</p>
            </div>

            {{-- Active --}}
            <label class="flex cursor-pointer flex-col justify-center gap-2 rounded-xl border border-base-300 bg-base-200/20 p-4 transition-all hover:border-primary/40 hover:bg-primary/[0.03]">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="font-semibold text-base-content">
                            Status <span class="text-error">*</span>
                        </div>
                        <p class="mt-1 text-xs text-base-content/50">Control technology availability</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="technology-status-badge" class="badge {{ $activeValue ? 'badge-success' : 'badge-ghost border-base-300' }} gap-1.5 text-xs font-medium">
                            <span class="h-1.5 w-1.5 rounded-full {{ $activeValue ? 'bg-success-content' : 'bg-base-content/40' }}"></span>
                            {{ $activeValue ? 'Live' : 'Hidden' }}
                        </span>
                        <input type="hidden" name="is_active" value="0">
                        <input
                            id="is_active"
                            type="checkbox"
                            name="is_active"
                            value="1"
                            class="toggle toggle-primary"
                            @checked((bool) $activeValue)
                            onchange="document.getElementById('technology-status-badge').outerHTML = this.checked ? '<span id=\'technology-status-badge\' class=\'badge badge-success gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-success-content\'></span>Live</span>' : '<span id=\'technology-status-badge\' class=\'badge badge-ghost border-base-300 gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-base-content/40\'></span>Hidden</span>'"
                        >
                    </div>
                </div>
                <div class="text-xs leading-5 text-base-content/55">Active technologies can be made available throughout CareerVault.</div>
                @error('is_active')
                    <p class="flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </label>
        </div>
    </section>

    {{-- Sticky Actions --}}
    <div class="sticky bottom-0 z-20 -mx-1 border-t border-base-300/80 bg-base-100/90 px-3 py-4 backdrop-blur-xl sm:border sm:px-5">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2 text-xs text-base-content/50">
                <i class="fa-solid fa-shield-halved text-success"></i>
                <span><span class="text-error">*</span> Required fields</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.technology.index') }}" class="btn btn-ghost flex-1 border border-base-300/70 sm:flex-none sm:px-6">Cancel</a>
                <button type="submit" class="btn btn-primary flex-2 gap-2 shadow-md shadow-primary/20 sm:flex-none sm:px-7">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ $isEdit ? 'Update Technology' : 'Create Technology' }}
                    <i class="fa-solid fa-arrow-right text-xs opacity-70"></i>
                </button>
            </div>
        </div>
    </div>
</form>