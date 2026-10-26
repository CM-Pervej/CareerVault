
@php
    $isEdit = $companyType->exists;
@endphp

@if ($errors->any())
    <div class="alert alert-error mb-4">
        <icon></icon>
        <div>
            <h3 class="font-semibold">Please correct the following errors:</h3>
            <ul class="list-inside list-disc text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<form
    method="POST"
    action="{{ $isEdit
        ? route('admin.company-types.update', $companyType)
        : route('admin.company-types.store') }}"
    class="space-y-5 pb-24"
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <section class="rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-semibold">Basic Information</h2>
            <p class="mt-1 text-sm text-base-content/60">
                Define the company type and explain what it represents.
            </p>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="name" class="label">
                    <span class="label-text font-medium">Company Type Name <span class="text-error">*</span></span>
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $companyType->name) }}"
                    class="input input-bordered w-full @error('name') input-error @enderror"
                    placeholder="e.g. Private Limited Company"
                    maxlength="255"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-base-content/60">
                    The URL slug is generated automatically from this name.
                </p>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="label">
                    <span class="label-text font-medium">Description</span>
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="textarea textarea-bordered w-full @error('description') textarea-error @enderror"
                    placeholder="Describe this type of business organization..."
                >{{ old('description', $companyType->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <section class="rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-semibold">Display Settings</h2>
            <p class="mt-1 text-sm text-base-content/60">
                Control the visibility and ordering of this company type.
            </p>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">
            <div>
                <label for="sort_order" class="label">
                    <span class="label-text font-medium">Sort Order <span class="text-error">*</span></span>
                </label>
                <input
                    id="sort_order"
                    name="sort_order"
                    type="number"
                    min="0"
                    step="1"
                    value="{{ old('sort_order', $companyType->sort_order ?? 0) }}"
                    class="input input-bordered w-full @error('sort_order') input-error @enderror"
                    required
                >
                @error('sort_order')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">
                    <span class="label-text font-medium">Status</span>
                </label>

                <input type="hidden" name="is_active" value="0">

                <label class="flex cursor-pointer items-center justify-between gap-4 rounded-lg border border-base-300 p-4">
                    <span>
                        <span class="block font-medium">Active</span>
                        <span class="block text-xs text-base-content/60">
                            Allow this type to be used in active listings.
                        </span>
                    </span>

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="toggle toggle-primary"
                        @checked((bool) old('is_active', $companyType->is_active ?? true))
                    >
                </label>
                @error('is_active')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <div class="sticky bottom-0 z-10 -mx-4 flex flex-col-reverse gap-3 border-t border-base-300 bg-base-100/95 px-4 py-4 backdrop-blur sm:-mx-6 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <a href="{{ route('admin.company-types.index') }}" class="btn btn-ghost">
            Cancel
        </a>

        <button type="submit" class="btn btn-primary">
            {{ $isEdit ? 'Save Changes' : 'Create Company Type' }}
        </button>
    </div>
</form>
