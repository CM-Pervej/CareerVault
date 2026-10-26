@php
    $isEdit = isset($companySize);
@endphp

<form method="POST"
      action="{{ $isEdit ? route('admin.company-sizes.update', $companySize) : route('admin.company-sizes.store') }}"
      class="space-y-5">

    @csrf

    @if ($isEdit)
        @method('PUT')
    @endif

    {{-- Basic Information --}}
    <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-4 py-4 sm:px-6">
            <h2 class="font-semibold text-base-content">Basic Information</h2>
            <p class="mt-1 text-sm text-base-content/60">
                Define the company size label and its unique identifier.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-4 sm:grid-cols-2 sm:p-6">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium">
                    Company Size Name <span class="text-error">*</span>
                </label>

                <input id="name" name="name" type="text" required maxlength="255"
                       value="{{ old('name', $companySize->name ?? '') }}"
                       placeholder="e.g. Small Business"
                       class="input input-bordered w-full @error('name') input-error @enderror">

                @error('name')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="mb-2 block text-sm font-medium">
                    Description
                </label>

                <textarea id="description" name="description" rows="3"
                          placeholder="Briefly describe this company size category..."
                          class="textarea textarea-bordered w-full @error('description') textarea-error @enderror">{{ old('description', $companySize->description ?? '') }}</textarea>

                @error('description')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Employee Range --}}
    <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-4 py-4 sm:px-6">
            <h2 class="font-semibold text-base-content">Employee Range</h2>
            <p class="mt-1 text-sm text-base-content/60">
                Specify the minimum and maximum number of employees.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-4 sm:grid-cols-2 sm:p-6">
            <div>
                <label for="min_employees" class="mb-2 block text-sm font-medium">
                    Minimum Employees <span class="text-error">*</span>
                </label>

                <input id="min_employees" name="min_employees" type="number" min="0" required
                       value="{{ old('min_employees', $companySize->min_employees ?? '') }}"
                       placeholder="e.g. 11"
                       class="input input-bordered w-full @error('min_employees') input-error @enderror">

                @error('min_employees')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="max_employees" class="mb-2 block text-sm font-medium">
                    Maximum Employees
                </label>

                <input id="max_employees" name="max_employees" type="number" min="0"
                       value="{{ old('max_employees', $companySize->max_employees ?? '') }}"
                       placeholder="Leave empty for no upper limit"
                       class="input input-bordered w-full @error('max_employees') input-error @enderror">

                @error('max_employees')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror

                <p class="mt-1 text-xs text-base-content/50">
                    Leave empty for ranges such as 1,001+ employees.
                </p>
            </div>
        </div>
    </section>

    {{-- Settings --}}
    <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-4 py-4 sm:px-6">
            <h2 class="font-semibold text-base-content">Settings</h2>
            <p class="mt-1 text-sm text-base-content/60">
                Configure the display order and availability of this category.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-4 sm:grid-cols-2 sm:p-6">
            <div>
                <label for="sort_order" class="mb-2 block text-sm font-medium">
                    Sort Order <span class="text-error">*</span>
                </label>

                <input id="sort_order" name="sort_order" type="number" min="0" required
                       value="{{ old('sort_order', $companySize->sort_order ?? 0) }}"
                       class="input input-bordered w-full @error('sort_order') input-error @enderror">

                @error('sort_order')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="is_active" class="mb-2 block text-sm font-medium">
                    Status <span class="text-error">*</span>
                </label>

                <select id="is_active" name="is_active" required
                        class="select select-bordered w-full @error('is_active') select-error @enderror">
                    <option value="1" @selected((string) old('is_active', isset($companySize) ? (int) $companySize->is_active : 1) === '1')>
                        Active
                    </option>
                    <option value="0" @selected((string) old('is_active', isset($companySize) ? (int) $companySize->is_active : 1) === '0')>
                        Inactive
                    </option>
                </select>

                @error('is_active')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Sticky Actions --}}
    <div class="sticky bottom-0 z-10 flex flex-col-reverse gap-3 rounded-xl border border-base-300 bg-base-100/95 p-4 shadow-lg backdrop-blur sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-base-content/50">
            <span class="text-error">*</span> Required fields
        </p>

        <div class="flex flex-col-reverse gap-2 sm:flex-row">
            <a href="{{ route('admin.company-sizes.index') }}"
               class="btn btn-ghost">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i>
                {{ $isEdit ? 'Update Company Size' : 'Create Company Size' }}
            </button>
        </div>
    </div>
</form>