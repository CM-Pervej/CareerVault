@php
    $isEdit = isset($companyType);
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.company-types.update', $companyType) : route('admin.company-types.store') }}" class="space-y-7">
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
                <p class="mt-0.5 text-xs text-base-content/50 sm:text-sm">Define the company type and explain what it represents.</p>
            </div>
            <span class="badge badge-ghost text-xs">01</span>
        </div>

        <div class="grid grid-cols-1 gap-6 p-5 sm:p-7">
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold text-base-content">
                    Company Type Name <span class="text-error">*</span>
                </label>
                <input id="name" name="name" type="text" required maxlength="255" value="{{ old('name', $companyType->name ?? '') }}" placeholder="e.g. Private Limited Company" class="input input-bordered h-12 w-full bg-base-100 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('name') input-error @enderror">
                @error('name')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
                <p class="mt-1.5 text-xs text-base-content/45">The URL slug is generated automatically from this name.</p>
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-semibold text-base-content">
                    Description <span class="ml-1 font-normal text-base-content/40">(Optional)</span>
                </label>
                <textarea id="description" name="description" rows="4" placeholder="Describe this type of business organization..." class="textarea textarea-bordered min-h-28 w-full bg-base-100 leading-6 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('description') textarea-error @enderror">{{ old('description', $companyType->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Display and Status --}}
    <section class="overflow-hidden">
        <div class="flex items-center gap-3 border-b border-base-300/80 px-5 sm:px-7">
            <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-secondary/10 text-secondary">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <div class="min-w-0 flex-1">
                <h2 class="font-bold text-base-content">Settings & Visibility</h2>
                <p class="mt-0.5 text-xs text-base-content/50 sm:text-sm">Control how this company type is organized and displayed.</p>
            </div>
            <span class="badge badge-ghost text-xs">02</span>
        </div>

        <div class="grid grid-cols-1 gap-6 p-5 sm:grid-cols-2 sm:p-7">
            <div>
                <label for="sort_order" class="mb-2 block text-sm font-semibold text-base-content">
                    Sort Order <span class="text-error">*</span>
                </label>
                <input id="sort_order" name="sort_order" type="number" min="0" step="1" value="{{ old('sort_order', $companyType->sort_order ?? 0) }}" class="input input-bordered h-12 w-full bg-base-100 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('sort_order') input-error @enderror" required>
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
                        <p class="mt-1 text-xs text-base-content/50">Control company type visibility</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="badge {{ old('is_active', isset($companyType) ? (int) $companyType->is_active : 1) ? 'badge-success' : 'badge-ghost border-base-300' }} gap-1.5 text-xs font-medium">
                            <span class="h-1.5 w-1.5 rounded-full {{ old('is_active', isset($companyType) ? (int) $companyType->is_active : 1) ? 'bg-success-content' : 'bg-base-content/40' }}"></span>
                            {{ old('is_active', isset($companyType) ? (int) $companyType->is_active : 1) ? 'Live' : 'Hidden' }}
                        </span>

                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary"
                            @checked((bool) old('is_active', isset($companyType) ? (int) $companyType->is_active : 1))
                            onchange="this.closest('label').querySelector('.badge').outerHTML = this.checked
                                ? '<span class=\'badge badge-success gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-success-content\'></span>Live</span>'
                                : '<span class=\'badge badge-ghost border-base-300 gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-base-content/40\'></span>Hidden</span>';">
                    </div>
                </div>
                <div class="text-xs leading-5 text-base-content/55">Active company types can be used in active listings.</div>
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
                <a href="{{ route('admin.company-types.index') }}" class="btn btn-ghost border border-base-300/70 sm:px-6 flex-1 sm:flex-none">Cancel</a>
                <button type="submit" class="btn btn-primary gap-2 shadow-md shadow-primary/20 sm:px-7 flex-2 sm:flex-none">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ $isEdit ? 'Update Company Type' : 'Create Company Type' }}
                    <i class="fa-solid fa-arrow-right text-xs opacity-70"></i>
                </button>
            </div>
        </div>
    </div>
</form>