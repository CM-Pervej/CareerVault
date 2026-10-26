@php
    $isEdit = isset($platform);
    $platform = $platform ?? null;
@endphp

<div class="space-y-8 mt-10">
    {{-- Basic Identity --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-base-content">Basic Identity</h2>
                    <p class="mt-0.5 text-sm text-base-content/60">Core information used to identify the platform.</p>
                </div>

                <span class="badge badge-ghost gap-1.5 border-base-300 text-xs font-medium text-base-content/60">
                    <i class="fa-solid fa-layer-group text-[11px]"></i> {{ $isEdit ? 'Editing' : 'New platform' }}
                </span>
            </div>
        </div>

        <div class="grid gap-5 p-6 md:grid-cols-2">
            <div class="form-control">
                <label for="name" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">
                        Platform Name <span class="text-error">*</span>
                    </span>
                </label>

                <input id="name" type="text" name="name" value="{{ old('name', $platform?->name) }}" class="input input-bordered w-full @error('name') input-error @enderror" placeholder="e.g. LinkedIn" required autofocus>

                @error('name')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label for="official_name" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Official Name</span>
                </label>

                <input id="official_name" type="text" name="official_name" value="{{ old('official_name', $platform?->official_name) }}" class="input input-bordered w-full @error('official_name') input-error @enderror" placeholder="e.g. LinkedIn Corporation">

                @error('official_name')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label for="base_url" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Website URL</span>
                </label>

                <label class="input input-bordered flex w-full items-center gap-2 @error('base_url') input-error @enderror">
                    <i class="fa-solid fa-globe text-xs text-base-content/40"></i>
                    <input id="base_url" type="url" name="base_url" value="{{ old('base_url', $platform?->base_url) }}" class="grow" placeholder="https://example.com">
                </label>

                @error('base_url')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label for="job_url" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Job URL</span>
                </label>

                <label class="input input-bordered flex w-full items-center gap-2 @error('job_url') input-error @enderror">
                    <i class="fa-solid fa-briefcase text-xs text-base-content/40"></i>
                    <input id="job_url" type="url" name="job_url" value="{{ old('job_url', $platform?->job_url) }}" class="grow" placeholder="https://example.com/jobs">
                </label>

                @error('job_url')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>
        </div>
    </section>

    {{-- Job & Business --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-base-content">Job &amp; Business Information</h2>
                <p class="mt-0.5 text-sm text-base-content/60">Define how this platform is used by job seekers.</p>
            </div>
        </div>

        <div class="grid gap-5 p-6 md:grid-co
            <div class="form-control">
                <label for="job_type" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">
                        Job Type <span class="text-error">*</span>
                    </span>
                </label>

                <select id="job_type" name="job_type" class="select select-bordered w-full @error('job_type') select-error @enderror" required>
                    <option value="Onsite" @selected(old('job_type', $platform?->job_type) === 'Onsite')>Onsite</option>
                    <option value="Remote" @selected(old('job_type', $platform?->job_type) === 'Remote')>Remote</option>
                    <option value="Both" @selected(old('job_type', $platform?->job_type ?? 'Both') === 'Both')>Both</option>
                </select>

                @error('job_type')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label for="business_model" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">
                        Business Model <span class="text-error">*</span>
                    </span>
                </label>

                <select id="business_model" name="business_model" class="select select-bordered w-full @error('business_model') select-error @enderror" required>
                    <option value="Free" @selected(old('business_model', $platform?->business_model ?? 'Free') === 'Free')>Free</option>
                    <option value="Freemium" @selected(old('business_model', $platform?->business_model) === 'Freemium')>Freemium</option>
                    <option value="Paid" @selected(old('business_model', $platform?->business_model) === 'Paid')>Paid</option>
                </select>

                @error('business_model')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="flex items-end">
                <label class="flex w-full cursor-pointer flex-col gap-1 rounded-xl border border-base-300 p-3 transition-colors hover:border-base-content/20">
                    <div class="flex items-center justify-between gap-3">
                        <div class="font-medium text-base-content">Account Required</div>
                        <input type="checkbox" name="account_required" value="1" class="toggle toggle-primary" @checked(old('account_required', $platform?->account_required))>
                    </div>

                    <div class="mt-0.5 text-sm text-base-content/60">Users need an account to use it.</div>
                </label>
            </div>
        </div>
    </section>

    {{-- Description --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-base-content">Description</h2>
                <p class="mt-0.5 text-sm text-base-content/60">Give users a clear understanding of the platform.</p>
            </div>
        </div>

        <div class="space-y-5 p-6">
            <div class="form-control">
                <label for="short_desc" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Short Description</span>
                    <span class="label-text-alt text-base-content/50">Max 500 characters</span>
                </label>

                <input id="short_desc" type="text" name="short_desc" maxlength="500" value="{{ old('short_desc', $platform?->short_desc) }}" class="input input-bordered w-full @error('short_desc') input-error @enderror" placeholder="A short summary of the platform...">

                @error('short_desc')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label for="description" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Full Description</span>
                </label>

                <textarea id="description" name="description" rows="7" class="textarea textarea-bordered w-full leading-relaxed @error('description') textarea-error @enderror" placeholder="Describe the platform, its purpose, audience, features, and anything important for job seekers..."> {{ old('description', $platform?->description) }} </textarea>

                @error('description')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>
        </div>
    </section>

    {{-- Branding --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <div>
                <h2 class="text-base font-semibold text-base-content">Branding &amp; Appearance</h2>
                <p class="mt-0.5 text-sm text-base-content/60">Visual assets used throughout CareerVault.</p>
            </div>
        </div>

        <div class="grid gap-5 p-6 md:grid-cols-2">
            <div class="form-control">
                <label for="color" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Brand Color</span>
                </label>

                <input id="color" type="text" name="color" value="{{ old('color', $platform?->color) }}" class="input input-bordered w-full @error('color') input-error @enderror" placeholder="#0A66C2 or primary">

                @error('color')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label for="icon" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Icon Class</span>
                </label>

                <input id="icon" type="text" name="icon" value="{{ old('icon', $platform?->icon) }}" class="input input-bordered w-full font-mono @error('icon') input-error @enderror" placeholder="fa-brands fa-linkedin">

                @error('icon')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2 col-span-2">
                {{-- Logo --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Logo</span>
                        <span class="label-text-alt text-base-content/50">JPG, PNG, WEBP</span>
                    </label>

                    <div class="rounded-lg border border-base-300 bg-base-200/30 p-4">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            <div class="relative shrink-0">
                                <div id="logo-preview-container" class="flex size-24 items-center justify-center overflow-hidden rounded-xl border border-base-300 bg-base-100">
                                    @if($isEdit && $platform->logo)
                                        <img id="logo-preview" src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-cover">
                                    @else
                                        <div id="logo-placeholder" class="text-center text-base-content/30">
                                            <i class="fa-solid fa-image text-2xl"></i>
                                            <div class="mt-1 text-xs">No image</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/webp" class="file-input file-input-bordered w-full">

                                <p id="logo-file-name" class="mt-2 truncate text-xs text-base-content/50">
                                    @if($isEdit && $platform->logo)
                                        Current logo will be kept unless a new image is selected.
                                    @else
                                        No image selected.
                                    @endif
                                </p>

                                @error('logo')
                                    <label class="label px-0 pb-0">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cover Image --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Cover Image</span>
                        <span class="label-text-alt text-base-content/50">JPG, PNG, WEBP</span>
                    </label>

                    <div class="rounded-lg border border-base-300 bg-base-200/30 p-4">
                        <div class="space-y-4">
                            <div id="cover-preview-container" class="relative flex h-40 w-full items-center justify-center overflow-hidden rounded-xl border border-base-300 bg-base-100">
                                @if($isEdit && $platform->cover_image)
                                    <img id="cover-preview" src="{{ Storage::url($platform->cover_image) }}" alt="{{ $platform->name }} cover image" class="size-full object-cover">
                                @else
                                    <div id="cover-placeholder" class="text-center text-base-content/30">
                                        <i class="fa-solid fa-image text-3xl"></i>
                                        <div class="mt-1 text-xs">No cover image</div>
                                    </div>
                                @endif
                            </div>

                            <input type="file" name="cover_image" id="cover_image" accept="image/jpeg,image/png,image/webp" class="file-input file-input-bordered w-full">

                            <p id="cover-file-name" class="truncate text-xs text-base-content/50">
                                @if($isEdit && $platform->cover_image)
                                    Current cover image will be kept unless a new image is selected.
                                @else
                                    No image selected.
                                @endif
                            </p>

                            @error('cover_image')
                                <label class="label px-0 pb-0">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </section>

    {{-- Classification & Availability --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <h2 class="text-base font-semibold text-base-content">Classification &amp; Availability</h2>
            <p class="mt-0.5 text-sm text-base-content/60">Control visibility, regional relevance, and ordering.</p>
        </div>

        <div class="space-y-5 p-6">
            <div class="grid gap-5 md:grid-cols-5">
                {{-- Sort Order --}}
                <div class="form-control">
                    <label for="sort_order" class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Sort Order <span class="text-error">*</span>
                        </span>
                    </label>

                    <input id="sort_order" type="number" name="sort_order" min="0" value="{{ old('sort_order', $platform?->sort_order ?? 0) }}" class="input input-bordered w-full @error('sort_order') input-error @enderror" required>

                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-base-content/50 text-sm text-justify text-wrap">Lower numbers appear first.</span>
                    </label>

                    @error('sort_order')
                        <label class="label pb-0 pt-0">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Bangladesh Focus --}}
                @php
                    $isBangladeshFocused = old(
                        'is_bangladesh_focused',
                        $platform?->is_bangladesh_focused ?? false
                    );
                @endphp

                <div class="form-control col-span-2">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">Bangladesh Focus</span>
                    </label>

                    <label class="flex cursor-pointer flex-col gap-1 rounded-xl border border-base-300 p-3 transition-colors hover:border-base-content/20">
                        <div class="flex items-center justify-between gap-3">
                            <div class="font-medium text-base-content">Bangladesh focused</div>
                            <div class="flex items-center gap-3">
                                <span class="badge {{ $isBangladeshFocused ? 'badge-success' : 'badge-ghost border-base-300' }} gap-1.5 text-xs font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $isBangladeshFocused ? 'bg-success-content' : 'bg-base-content/40' }}"></span>
                                    {{ $isBangladeshFocused ? 'Focused' : 'General' }}
                                </span>

                                <input type="checkbox" name="is_bangladesh_focused" value="1" class="checkbox checkbox-primary" 
                                    @checked($isBangladeshFocused)
                                    onchange="this.closest('label').querySelector('.badge').outerHTML = this.checked
                                        ? '<span class=\'badge badge-success gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-success-content\'></span>Focused</span>'
                                        : '<span class=\'badge badge-ghost border-base-300 gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-base-content/40\'></span>General</span>';"
                                >
                            </div>
                        </div>

                        <div class="mt-0.5 text-sm text-base-content/60">Mark this platform as primarily relevant to Bangladesh.</div>
                    </label>

                    @error('is_bangladesh_focused')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Active Platform --}}
                @php
                    $isActive = old(
                        'is_active',
                        $platform?->is_active ?? true
                    );
                @endphp

                <div class="form-control col-span-2">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">Status</span>
                    </label>

                    <label class="flex cursor-pointer flex-col gap-1 rounded-xl border border-base-300 p-3 transition-colors hover:border-base-content/20">
                        <div class="flex items-center justify-between gap-3">
                            <div class="font-medium text-base-content">Active platform</div>
                            <div class="flex items-center gap-3">
                                <span class="badge {{ $isActive ? 'badge-success' : 'badge-ghost border-base-300' }} gap-1.5 text-xs font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $isActive ? 'bg-success-content' : 'bg-base-content/40' }}"></span>
                                    {{ $isActive ? 'Live' : 'Hidden' }}
                                </span>

                                <input type="hidden" name="is_active" value="0">

                                <input type="checkbox" name="is_active" value="1" class="toggle toggle-success"
                                    @checked($isActive)
                                    onchange="this.closest('label').querySelector('.badge').outerHTML = this.checked
                                        ? '<span class=\'badge badge-success gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-success-content\'></span>Live</span>'
                                        : '<span class=\'badge badge-ghost border-base-300 gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-base-content/40\'></span>Hidden</span>';"
                                >
                            </div>
                        </div>

                        <div class="mt-0.5 text-sm text-base-content/60">Active platforms are available in the public directory.</div>
                    </label>

                    @error('is_active')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    {{-- History & Verification --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <h2 class="text-base font-semibold text-base-content">History &amp; Verification</h2>
            <p class="mt-0.5 text-sm text-base-content/60">Historical information and data verification status.</p>
        </div>

        <div class="grid gap-5 p-6 md:grid-cols-4">
            <div class="form-control">
                <label for="founded_month" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Founded Month</span>
                </label>

                <select id="founded_month" name="founded_month" class="select select-bordered w-full @error('founded_month') select-error @enderror">
                    <option value="">Unknown</option>

                    @foreach([
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December'
                    ] as $month => $monthName)
                        <option value="{{ $month }}" @selected((string) old('founded_month', $platform?->founded_month) === (string) $month)> {{ $monthName }} </option>
                    @endforeach
                </select>

                @error('founded_month')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label for="founded_year" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Founded Year</span>
                </label>

                <input id="founded_year" type="number" name="founded_year" min="1800" max="{{ date('Y') }}" value="{{ old('founded_year', $platform?->founded_year) }}" class="input input-bordered w-full @error('founded_year') input-error @enderror" placeholder="{{ date('Y') }}">

                @error('founded_year')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label for="founded_by" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Founded By</span>
                </label>

                <input id="founded_by" type="text" name="founded_by" value="{{ old('founded_by', $platform?->founded_by) }}" class="input input-bordered w-full @error('founded_by') input-error @enderror" placeholder="Founder name">

                @error('founded_by')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label for="last_verified_at" class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Last Verified</span>
                </label>

                <input id="last_verified_at" type="datetime-local" name="last_verified_at" value="{{ old('last_verified_at', $platform?->last_verified_at?->format('Y-m-d\TH:i')) }}" class="input input-bordered w-full @error('last_verified_at') input-error @enderror">

                <label class="label pb-0 pt-1.5">
                    <span class="label-text-alt text-base-content/50 text-xs text-wrap text-justify">When someone last confirmed this platform's information.</span>
                </label>

                @error('last_verified_at')
                    <label class="label pb-0 pt-0">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>
        </div>
    </section>
</div>

{{-- Actions --}}
<div class="sticky bottom-0 -mx-6 mt-8 flex flex-wrap justify-end gap-3 border-t border-base-300 bg-base-100/95 px-6 py-4 backdrop-blur">
    <a href="{{ $isEdit ? route('admin.platforms.show', $platform) : route('admin.platforms.index') }}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary gap-2">
        <i class="fa-solid {{ $isEdit ? 'fa-floppy-disk' : 'fa-plus' }}"></i>
        {{ $isEdit ? 'Save Changes' : 'Create Platform' }}
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const setupPreview = (inputId, previewContainerId, fileNameId, placeholderId) => {
            const input = document.getElementById(inputId);
            const container = document.getElementById(previewContainerId);
            const fileName = document.getElementById(fileNameId);

            if (!input || !container || !fileName) return;

            input.addEventListener('change', () => {
                const file = input.files?.[0];

                if (!file) {
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    return;
                }

                const reader = new FileReader();

                reader.onload = event => {
                    container.innerHTML = `
                        <img src="${event.target.result}"
                            alt="Selected image"
                            class="size-full object-cover">
                    `;

                    fileName.textContent = `New image: ${file.name}`;
                };

                reader.readAsDataURL(file);
            });
        };

        setupPreview(
            'logo',
            'logo-preview-container',
            'logo-file-name',
            'logo-placeholder'
        );

        setupPreview(
            'cover_image',
            'cover-preview-container',
            'cover-file-name',
            'cover-placeholder'
        );
    });
</script>