@php
    $editing = isset($company);
    $selectedParent = old('parent_company_id', $company->parent_company_id ?? '');

    $emailsText = old('emails_text', implode("\n", $company->emails ?? []));
    $phonesText = old('phones_text', implode("\n", $company->phones ?? []));

    $operatingStatuses = [
        'operating' => 'Operating',
        'acquired' => 'Acquired',
        'merged' => 'Merged',
        'closed' => 'Closed',
        'dissolved' => 'Dissolved',
        'unknown' => 'Unknown',
    ];

    $recordStatuses = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'draft' => 'Draft',
        'archived' => 'Archived',
    ];
@endphp

<form method="POST"
    action="{{ $editing ? route('admin.companies.update', $company) : route('admin.companies.store') }}"
    enctype="multipart/form-data"
    class="space-y-5">

    @csrf

    @if ($editing)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div class="alert alert-error items-start">
            <i class="fa-solid fa-circle-exclamation mt-1"></i>
            <div>
                <p class="font-semibold">Please correct the following errors.</p>
                <ul class="mt-1 list-disc space-y-1 pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Basic information --}}
    <section class="rounded-xl border border-base-300 bg-base-100">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-semibold">Basic Information</h2>
            <p class="mt-1 text-sm text-base-content/60">Company identity and public description.</p>
        </div>

        <div class="grid gap-4 p-5 md:grid-cols-2">
            <label class="form-control">
                <span class="label-text mb-1">Company Name <span class="text-error">*</span></span>
                <input type="text" name="name" value="{{ old('name', $company->name ?? '') }}"
                    class="input input-bordered w-full" required maxlength="255">
                @error('name')
                    <span class="mt-1 text-xs text-error">{{ $message }}</span>
                @enderror
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Legal Name</span>
                <input type="text" name="legal_name" value="{{ old('legal_name', $company->legal_name ?? '') }}"
                    class="input input-bordered w-full" maxlength="255">
                @error('legal_name')
                    <span class="mt-1 text-xs text-error">{{ $message }}</span>
                @enderror
            </label>

            <label class="form-control md:col-span-2">
                <span class="label-text mb-1">Tagline</span>
                <input type="text" name="tagline" value="{{ old('tagline', $company->tagline ?? '') }}"
                    class="input input-bordered w-full" maxlength="255"
                    placeholder="A short phrase describing the company">
            </label>

            <label class="form-control md:col-span-2">
                <span class="label-text mb-1">Short Description</span>
                <textarea name="short_desc" rows="3" maxlength="10000"
                    class="textarea textarea-bordered w-full">{{ old('short_desc', $company->short_desc ?? '') }}</textarea>
            </label>

            <label class="form-control md:col-span-2">
                <span class="label-text mb-1">Full Description</span>
                <textarea name="description" rows="6"
                    class="textarea textarea-bordered w-full">{{ old('description', $company->description ?? '') }}</textarea>
            </label>
        </div>
    </section>

    {{-- Branding --}}
    <section class="rounded-xl border border-base-300 bg-base-100">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-semibold">Branding</h2>
            <p class="mt-1 text-sm text-base-content/60">Company logo and cover image.</p>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">
            <div class="space-y-3">
                <label class="form-control">
                    <span class="label-text mb-1">Company Logo</span>
                    <input type="file" name="logo" accept="image/jpeg,image/png,image/webp"
                        class="file-input file-input-bordered w-full">
                    <span class="label-text-alt mt-1">JPG, PNG or WebP; maximum 2 MB.</span>
                </label>

                @if ($editing && $company->logo)
                    <div class="flex items-center gap-3">
                        <img src="{{ Storage::disk('public')->url($company->logo) }}"
                            alt="{{ $company->name }} logo"
                            class="h-20 w-20 rounded-xl border border-base-300 bg-base-100 object-contain p-2">
                        <span class="text-xs text-base-content/60">Current logo</span>
                    </div>
                @endif
            </div>

            <div class="space-y-3">
                <label class="form-control">
                    <span class="label-text mb-1">Cover Image</span>
                    <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
                        class="file-input file-input-bordered w-full">
                    <span class="label-text-alt mt-1">JPG, PNG or WebP; maximum 5 MB.</span>
                </label>

                @if ($editing && $company->cover_image)
                    <div class="space-y-2">
                        <img src="{{ Storage::disk('public')->url($company->cover_image) }}"
                            alt="{{ $company->name }} cover"
                            class="h-24 w-full rounded-lg border border-base-300 object-cover">
                        <span class="text-xs text-base-content/60">Current cover image</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Websites and contacts --}}
    <section class="rounded-xl border border-base-300 bg-base-100">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-semibold">Websites and Contact Information</h2>
            <p class="mt-1 text-sm text-base-content/60">Enter one email address or phone number per line.</p>
        </div>

        <div class="grid gap-4 p-5 md:grid-cols-2">
            <label class="form-control">
                <span class="label-text mb-1">Official Website</span>
                <input type="url" name="website" value="{{ old('website', $company->website ?? '') }}"
                    class="input input-bordered w-full" maxlength="2048"
                    placeholder="https://example.com">
                @error('website')
                    <span class="mt-1 text-xs text-error">{{ $message }}</span>
                @enderror
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Career Page</span>
                <input type="url" name="career_page" value="{{ old('career_page', $company->career_page ?? '') }}"
                    class="input input-bordered w-full" maxlength="2048"
                    placeholder="https://example.com/careers">
                @error('career_page')
                    <span class="mt-1 text-xs text-error">{{ $message }}</span>
                @enderror
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Email Addresses</span>
                <textarea name="emails_text" rows="4" class="textarea textarea-bordered w-full"
                    placeholder="careers@example.com&#10;info@example.com">{{ $emailsText }}</textarea>
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Phone Numbers</span>
                <textarea name="phones_text" rows="4" class="textarea textarea-bordered w-full"
                    placeholder="+8801XXXXXXXXX&#10;+1XXXXXXXXXX">{{ $phonesText }}</textarea>
            </label>
        </div>
    </section>

    {{-- Founding and size --}}
    <section class="rounded-xl border border-base-300 bg-base-100">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-semibold">Founding and Company Size</h2>
            <p class="mt-1 text-sm text-base-content/60">Founding details and workforce information.</p>
        </div>

        <div class="grid gap-4 p-5 md:grid-cols-2 lg:grid-cols-3">
            <label class="form-control">
                <span class="label-text mb-1">Founder / Founded By</span>
                <input type="text" name="founded_by" value="{{ old('founded_by', $company->founded_by ?? '') }}"
                    class="input input-bordered w-full" maxlength="255">
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Founding Month</span>
                <select name="founded_month" class="select select-bordered w-full">
                    <option value="">Select month</option>
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}" @selected((string) old('founded_month', $company->founded_month ?? '') === (string) $month)>
                            {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Founding Year</span>
                <input type="number" name="founded_year" min="1800" max="{{ now()->year }}"
                    value="{{ old('founded_year', $company->founded_year ?? '') }}"
                    class="input input-bordered w-full">
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Employee Count</span>
                <input type="number" name="employee_count" min="0"
                    value="{{ old('employee_count', $company->employee_count ?? '') }}"
                    class="input input-bordered w-full">
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Company Size</span>
                <select name="company_size_id" class="select select-bordered w-full">
                    <option value="">Select company size</option>
                    @foreach ($companySizes as $size)
                        <option value="{{ $size->id }}" @selected((string) old('company_size_id', $company->company_size_id ?? '') === (string) $size->id)>
                            {{ $size->name }}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>
    </section>

    {{-- Company classification --}}
    <section class="rounded-xl border border-base-300 bg-base-100">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-semibold">Classification and Corporate Hierarchy</h2>
            <p class="mt-1 text-sm text-base-content/60">Set the company's type, ownership, primary industry, and parent company.</p>
        </div>

        <div class="grid gap-4 p-5 md:grid-cols-2">
            <label class="form-control">
                <span class="label-text mb-1">Company Type</span>
                <select name="company_type_id" class="select select-bordered w-full">
                    <option value="">Select company type</option>
                    @foreach ($companyTypes as $type)
                        <option value="{{ $type->id }}" @selected((string) old('company_type_id', $company->company_type_id ?? '') === (string) $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Ownership Type</span>
                <select name="ownership_type_id" class="select select-bordered w-full">
                    <option value="">Select ownership type</option>
                    @foreach ($ownershipTypes as $type)
                        <option value="{{ $type->id }}" @selected((string) old('ownership_type_id', $company->ownership_type_id ?? '') === (string) $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Primary Industry</span>
                <select name="primary_industry_id" class="select select-bordered w-full">
                    <option value="">Select primary industry</option>
                    @foreach ($industries as $industry)
                        <option value="{{ $industry->id }}" @selected((string) old('primary_industry_id', $company->primary_industry_id ?? '') === (string) $industry->id)>
                            {{ $industry->name }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Parent Company</span>
                <select name="parent_company_id" class="select select-bordered w-full">
                    <option value="">No parent company</option>
                    @foreach ($parentCompanies as $parent)
                        @if (!$editing || (int) $parent->id !== (int) $company->id)
                            <option value="{{ $parent->id }}" @selected((string) $selectedParent === (string) $parent->id)>
                                {{ $parent->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </label>
        </div>
    </section>

    {{-- Pivot-table relationships --}}
    <section class="space-y-2">
        <div class="px-1 pb-1">
            <h2 class="font-semibold">Company Relationships</h2>
            <p class="mt-1 text-sm text-base-content/60">
                Manage associated business models, industries, technologies, locations, and platform profiles.
            </p>
        </div>

        @include('admin.company.company._relationships')
    </section>

    {{-- Status and verification --}}
    <section class="rounded-xl border border-base-300 bg-base-100">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-semibold">Status and Verification</h2>
            <p class="mt-1 text-sm text-base-content/60">Control visibility and verification information for this company.</p>
        </div>

        <div class="grid gap-4 p-5 md:grid-cols-2">
            <label class="form-control">
                <span class="label-text mb-1">Record Status <span class="text-error">*</span></span>
                <select name="status" class="select select-bordered w-full" required>
                    @foreach ($recordStatuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $company->status ?? 'active') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Operating Status</span>
                <select name="operating_status" class="select select-bordered w-full">
                    <option value="">Not specified</option>
                    @foreach ($operatingStatuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('operating_status', $company->operating_status ?? '') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="form-control">
                <span class="label-text mb-1">Last Verified At</span>
                <input type="datetime-local" name="last_verified_at"
                    value="{{ old('last_verified_at', isset($company->last_verified_at) ? $company->last_verified_at->format('Y-m-d\TH:i') : '') }}"
                    class="input input-bordered w-full">
            </label>

            <div class="space-y-4 pt-2">
                <label class="flex items-center gap-3">
                    <input type="hidden" name="is_verified" value="0">
                    <input type="checkbox" name="is_verified" value="1"
                        class="checkbox checkbox-primary"
                        @checked((bool) old('is_verified', $company->is_verified ?? false))>
                    <span>
                        <span class="block font-medium">Verified Company</span>
                        <span class="text-xs text-base-content/60">Company information has been checked.</span>
                    </span>
                </label>

                <label class="flex items-center gap-3">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1"
                        class="checkbox checkbox-primary"
                        @checked((bool) old('is_featured', $company->is_featured ?? false))>
                    <span>
                        <span class="block font-medium">Featured Company</span>
                        <span class="text-xs text-base-content/60">Highlight this company in the application.</span>
                    </span>
                </label>
            </div>

            <label class="form-control md:col-span-2">
                <span class="label-text mb-1">Internal Notes</span>
                <textarea name="notes" rows="4"
                    class="textarea textarea-bordered w-full">{{ old('notes', $company->notes ?? '') }}</textarea>
            </label>
        </div>
    </section>

    {{-- Actions --}}
    <div class="sticky bottom-0 z-10 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-base-300 bg-base-100/95 p-4 shadow-lg backdrop-blur">
        <a href="{{ $editing ? route('admin.companies.show', $company) : route('admin.companies.index') }}"
            class="btn btn-ghost">
            Cancel
        </a>

        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk mr-2"></i>
            {{ $editing ? 'Save Changes' : 'Create Company' }}
        </button>
    </div>
</form>