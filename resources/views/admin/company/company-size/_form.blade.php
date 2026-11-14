@php
    $isEdit = isset($companySize);
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.company-sizes.update', $companySize) : route('admin.company-sizes.store') }}" class="space-y-7">
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
                <p class="mt-0.5 text-xs text-base-content/50 sm:text-sm">Establish the identity of this company size category.</p>
            </div>

            <span class="badge badge-ghost text-xs">01</span>
        </div>

        <div class="grid grid-cols-1 gap-6 p-5 sm:grid-cols-2 sm:p-7">
            <div class="sm:col-span-2">
                <label for="name" class="mb-2 block text-sm font-semibold text-base-content">
                    Company Size Name <span class="text-error">*</span>
                </label>

                <input id="name" name="name" type="text" required maxlength="255" value="{{ old('name', $companySize->name ?? '') }}" placeholder="e.g. Small Business" class="input input-bordered h-12 w-full bg-base-100 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('name') input-error @enderror">

                @error('name')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror

                <p class="mt-1.5 text-xs text-base-content/45">Choose a clear and recognizable classification name.</p>
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="mb-2 block text-sm font-semibold text-base-content">
                    Description <span class="ml-1 font-normal text-base-content/40">(Optional)</span>
                </label>

                <textarea id="description" name="description" rows="4" placeholder="Briefly describe this company size category..." class="textarea textarea-bordered min-h-28 w-full bg-base-100 leading-6 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('description') textarea-error @enderror">{{ old('description', $companySize->description ?? '') }}</textarea>

                @error('description')
                    <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Employee Range --}}
    <section class="overflow-hidden">
        <div class="flex items-center gap-3 border-b border-base-300/80 px-5 sm:px-7">
            <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-info/10 text-info">
                <i class="fa-solid fa-users"></i>
            </div>

            <div class="min-w-0 flex-1">
                <h2 class="font-bold text-base-content">Employee Range</h2>
                <p class="mt-0.5 text-xs text-base-content/50 sm:text-sm">Define the workforce boundaries for this classification.</p>
            </div>

            <span class="badge badge-ghost text-xs">02</span>
        </div>

        <div class="p-5 sm:p-7">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="min_employees" class="mb-2 block text-sm font-semibold text-base-content">
                        Minimum Employees <span class="text-error">*</span>
                    </label>

                    <div class="relative">
                        <input id="min_employees" name="min_employees" type="number" min="0" required value="{{ old('min_employees', $companySize->min_employees ?? '') }}" placeholder="e.g. 11" class="input input-bordered h-12 w-full bg-base-100 pr-16 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('min_employees') input-error @enderror">

                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-medium text-base-content/40">People</span>
                    </div>

                    @error('min_employees')
                        <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="max_employees" class="mb-2 block text-sm font-semibold text-base-content">
                        Maximum Employees <span class="ml-1 font-normal text-base-content/40">(Optional)</span>
                    </label>

                    <div class="relative">
                        <input id="max_employees" name="max_employees" type="number" min="0" value="{{ old('max_employees', $companySize->max_employees ?? '') }}" placeholder="e.g. 50" class="input input-bordered h-12 w-full bg-base-100 pr-16 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('max_employees') input-error @enderror">

                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-xs font-medium text-base-content/40">People</span>
                    </div>

                    @error('max_employees')
                        <p class="mt-1.5 flex items-center gap-1.5 text-xs font-medium text-error">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-1.5 text-xs text-base-content/45">Leave empty for ranges such as 1,001+ employees.</p>
                </div>
            </div>

            {{-- Range Illustration --}}
            <div class="mt-7 flex items-start gap-3 rounded-xl border border-info/20 bg-info/5 p-4">
                <i class="fa-solid fa-lightbulb mt-0.5 text-info"></i>
                <div>
                    <p class="text-sm font-semibold text-base-content">Range configuration</p>
                    <p class="mt-1 text-xs leading-5 text-base-content/60">Set a minimum and maximum employee count to establish the boundaries of this category. An empty maximum represents an unlimited upper range.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Settings --}}
    <section class="overflow-hidden">
        <div class="flex items-center gap-3 border-b border-base-300/80 px-5 sm:px-7">
            <div class="grid size-10 shrink-0 place-items-center rounded-xl bg-secondary/10 text-secondary">
                <i class="fa-solid fa-sliders"></i>
            </div>

            <div class="min-w-0 flex-1">
                <h2 class="font-bold text-base-content">Settings & Visibility</h2>
                <p class="mt-0.5 text-xs text-base-content/50 sm:text-sm">Control how this classification is organized and displayed.</p>
            </div>

            <span class="badge badge-ghost text-xs">03</span>
        </div>

        <div class="grid grid-cols-1 gap-6 p-5 sm:grid-cols-2 sm:p-7">
            <div>
                <label for="sort_order" class="mb-2 block text-sm font-semibold text-base-content">
                    Sort Order <span class="text-error">*</span>
                </label>

                <input id="sort_order" name="sort_order" type="number" min="0" required value="{{ old('sort_order', $companySize->sort_order ?? 0) }}" class="input input-bordered h-12 w-full bg-base-100 transition-all focus:border-primary focus:outline-4 focus:outline-primary/10 @error('sort_order') input-error @enderror">

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
                        <p class="mt-1 text-xs text-base-content/50">Control category visibility</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="badge {{ old('is_active', isset($companySize) ? (int) $companySize->is_active : 1) ? 'badge-success' : 'badge-ghost border-base-300' }} gap-1.5 text-xs font-medium">
                            <span class="h-1.5 w-1.5 rounded-full {{ old('is_active', isset($companySize) ? (int) $companySize->is_active : 1) ? 'bg-success-content' : 'bg-base-content/40' }}"></span>
                            {{ old('is_active', isset($companySize) ? (int) $companySize->is_active : 1) ? 'Live' : 'Hidden' }}
                        </span>

                        <input type="hidden" name="is_active" value="0">

                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary"
                            @checked((bool) old('is_active', isset($companySize) ? (int) $companySize->is_active : 1))
                            onchange="this.closest('label').querySelector('.badge').outerHTML = this.checked
                                ? '<span class=\'badge badge-success gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-success-content\'></span>Live</span>'
                                : '<span class=\'badge badge-ghost border-base-300 gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-base-content/40\'></span>Hidden</span>';">
                    </div>
                </div>

                <div class="text-xs leading-5 text-base-content/55">Active categories can be displayed throughout CareerVault.</div>

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
                <a href="{{ route('admin.company-sizes.index') }}" class="btn btn-ghost border border-base-300/70 sm:px-6 flex-1 sm:flex-none">Cancel</a>

                <button type="submit" class="btn btn-primary gap-2 shadow-md shadow-primary/20 sm:px-7 flex-2 sm:flex-none">
                    <i class="fa-solid fa-floppy-disk"></i>
                    {{ $isEdit ? 'Update Company Size' : 'Create Company Size' }}
                    <i class="fa-solid fa-arrow-right text-xs opacity-70"></i>
                </button>
            </div>
        </div>
    </div>
</form>