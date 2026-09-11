@php
    $isEdit=isset($platform);
    $platform=$platform??null;
@endphp

<div class="space-y-5">

    {{-- Basic Identity --}}
    <section class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-primary/10 text-primary">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <h2 class="font-bold">Basic Identity</h2>
                    <p class="text-xs text-base-content/50">
                        Core information used to identify the platform.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">

            <div>
                <label for="name" class="label">
                    <span class="label-text font-medium">
                        Platform Name <span class="text-error">*</span>
                    </span>
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name',$platform?->name) }}"
                    class="input input-bordered w-full @error('name') input-error @enderror"
                    placeholder="e.g. LinkedIn"
                    required
                    autofocus
                >

                @error('name')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label for="official_name" class="label">
                    <span class="label-text font-medium">Official Name</span>
                </label>

                <input
                    id="official_name"
                    type="text"
                    name="official_name"
                    value="{{ old('official_name',$platform?->official_name) }}"
                    class="input input-bordered w-full @error('official_name') input-error @enderror"
                    placeholder="e.g. LinkedIn Corporation"
                >

                @error('official_name')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </section>


    {{-- URLs --}}
    <section class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-info/10 text-info">
                    <i class="fa-solid fa-link"></i>
                </div>
                <div>
                    <h2 class="font-bold">Platform URLs</h2>
                    <p class="text-xs text-base-content/50">
                        Official website and job-search destination.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">

            <div>
                <label for="base_url" class="label">
                    <span class="label-text font-medium">Website URL</span>
                </label>

                <label class="input input-bordered flex items-center gap-2 @error('base_url') input-error @enderror">
                    <i class="fa-solid fa-globe text-base-content/40"></i>

                    <input
                        id="base_url"
                        type="url"
                        name="base_url"
                        value="{{ old('base_url',$platform?->base_url) }}"
                        class="grow"
                        placeholder="https://example.com"
                    >
                </label>

                @error('base_url')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label for="job_url" class="label">
                    <span class="label-text font-medium">Job URL</span>
                </label>

                <label class="input input-bordered flex items-center gap-2 @error('job_url') input-error @enderror">
                    <i class="fa-solid fa-briefcase text-base-content/40"></i>

                    <input
                        id="job_url"
                        type="url"
                        name="job_url"
                        value="{{ old('job_url',$platform?->job_url) }}"
                        class="grow"
                        placeholder="https://example.com/jobs"
                    >
                </label>

                @error('job_url')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </section>


    {{-- Job & Business --}}
    <section class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-success/10 text-success">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <h2 class="font-bold">Job & Business Information</h2>
                    <p class="text-xs text-base-content/50">
                        Define how this platform is used by job seekers.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-3">

            <div>
                <label for="job_type" class="label">
                    <span class="label-text font-medium">
                        Job Type <span class="text-error">*</span>
                    </span>
                </label>

                <select
                    id="job_type"
                    name="job_type"
                    class="select select-bordered w-full @error('job_type') select-error @enderror"
                    required
                >
                    <option value="Onsite" @selected(old('job_type',$platform?->job_type)==='Onsite')>
                        Onsite
                    </option>
                    <option value="Remote" @selected(old('job_type',$platform?->job_type)==='Remote')>
                        Remote
                    </option>
                    <option value="Both" @selected(old('job_type',$platform?->job_type??'Both')==='Both')>
                        Both
                    </option>
                </select>

                @error('job_type')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label for="business_model" class="label">
                    <span class="label-text font-medium">
                        Business Model <span class="text-error">*</span>
                    </span>
                </label>

                <select
                    id="business_model"
                    name="business_model"
                    class="select select-bordered w-full @error('business_model') select-error @enderror"
                    required
                >
                    <option value="Free" @selected(old('business_model',$platform?->business_model??'Free')==='Free')>
                        Free
                    </option>
                    <option value="Freemium" @selected(old('business_model',$platform?->business_model)==='Freemium')>
                        Freemium
                    </option>
                    <option value="Paid" @selected(old('business_model',$platform?->business_model)==='Paid')>
                        Paid
                    </option>
                </select>

                @error('business_model')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div class="flex items-end pb-1">
                <label class="flex w-full cursor-pointer items-center justify-between rounded-xl border border-base-300 px-4 py-3">
                    <div>
                        <div class="text-sm font-medium">Account Required</div>
                        <div class="text-xs text-base-content/50">
                            Users need an account to use it.
                        </div>
                    </div>

                    <input
                        type="checkbox"
                        name="account_required"
                        value="1"
                        class="toggle toggle-primary"
                        @checked(old('account_required',$platform?->account_required))
                    >
                </label>
            </div>

        </div>
    </section>


    {{-- Description --}}
    <section class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-secondary/10 text-secondary">
                    <i class="fa-solid fa-align-left"></i>
                </div>
                <div>
                    <h2 class="font-bold">Description</h2>
                    <p class="text-xs text-base-content/50">
                        Give users a clear understanding of the platform.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-5 p-5">

            <div>
                <label for="short_desc" class="label">
                    <span class="label-text font-medium">Short Description</span>
                    <span class="label-text-alt opacity-50">Max 500 characters</span>
                </label>

                <input
                    id="short_desc"
                    type="text"
                    name="short_desc"
                    value="{{ old('short_desc',$platform?->short_desc) }}"
                    class="input input-bordered w-full @error('short_desc') input-error @enderror"
                    placeholder="A short summary of the platform..."
                >

                @error('short_desc')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label for="description" class="label">
                    <span class="label-text font-medium">Full Description</span>
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="7"
                    class="textarea textarea-bordered w-full leading-relaxed @error('description') textarea-error @enderror"
                    placeholder="Describe the platform, its purpose, audience, features, and anything important for job seekers..."
                >{{ old('description',$platform?->description) }}</textarea>

                @error('description')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </section>


    {{-- Branding --}}
    <section class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-warning/10 text-warning">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <div>
                    <h2 class="font-bold">Branding & Appearance</h2>
                    <p class="text-xs text-base-content/50">
                        Visual assets used throughout CareerVault.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">

            <div>
                <label for="color" class="label">
                    <span class="label-text font-medium">Brand Color</span>
                </label>

                <input
                    id="color"
                    type="text"
                    name="color"
                    value="{{ old('color',$platform?->color) }}"
                    class="input input-bordered w-full @error('color') input-error @enderror"
                    placeholder="#0A66C2 or primary"
                >

                @error('color')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label for="icon" class="label">
                    <span class="label-text font-medium">Icon Class</span>
                </label>

                <input
                    id="icon"
                    type="text"
                    name="icon"
                    value="{{ old('icon',$platform?->icon) }}"
                    class="input input-bordered w-full font-mono @error('icon') input-error @enderror"
                    placeholder="fa-brands fa-linkedin"
                >

                @error('icon')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label for="logo" class="label">
                    <span class="label-text font-medium">Logo</span>
                </label>

                <input
                    id="logo"
                    type="text"
                    name="logo"
                    value="{{ old('logo',$platform?->logo) }}"
                    class="input input-bordered w-full @error('logo') input-error @enderror"
                    placeholder="/images/platforms/linkedin.png"
                >

                @error('logo')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label for="cover_image" class="label">
                    <span class="label-text font-medium">Cover Image</span>
                </label>

                <input
                    id="cover_image"
                    type="text"
                    name="cover_image"
                    value="{{ old('cover_image',$platform?->cover_image) }}"
                    class="input input-bordered w-full @error('cover_image') input-error @enderror"
                    placeholder="/images/platforms/linkedin-cover.jpg"
                >

                @error('cover_image')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </section>


    {{-- Classification --}}
    <section class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-accent/10 text-accent">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h2 class="font-bold">Classification & Availability</h2>
                    <p class="text-xs text-base-content/50">
                        Control visibility, regional relevance, and ordering.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-3">

            <div>
                <label for="sort_order" class="label">
                    <span class="label-text font-medium">
                        Sort Order <span class="text-error">*</span>
                    </span>
                </label>

                <input
                    id="sort_order"
                    type="number"
                    name="sort_order"
                    min="0"
                    value="{{ old('sort_order',$platform?->sort_order??0) }}"
                    class="input input-bordered w-full @error('sort_order') input-error @enderror"
                    required
                >

                <p class="mt-1 text-xs text-base-content/40">
                    Lower numbers appear first.
                </p>

                @error('sort_order')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div class="flex items-end pb-1">
                <label class="flex w-full cursor-pointer items-center justify-between rounded-xl border border-base-300 px-4 py-3">
                    <div>
                        <div class="text-sm font-medium">Active Platform</div>
                        <div class="text-xs text-base-content/50">
                            Available in the public directory.
                        </div>
                    </div>

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="toggle toggle-success"
                        @checked(old('is_active',$platform?->is_active??true))
                    >
                </label>
            </div>


            <div class="flex items-end pb-1">
                <label class="flex w-full cursor-pointer items-center justify-between rounded-xl border border-base-300 px-4 py-3">
                    <div>
                        <div class="text-sm font-medium">Bangladesh Focused</div>
                        <div class="text-xs text-base-content/50">
                            Primarily relevant to Bangladesh.
                        </div>
                    </div>

                    <input
                        type="checkbox"
                        name="is_bangladesh_focused"
                        value="1"
                        class="toggle toggle-primary"
                        @checked(old('is_bangladesh_focused',$platform?->is_bangladesh_focused))
                    >
                </label>
            </div>

        </div>
    </section>


    {{-- Founded & Verification --}}
    <section class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="grid h-9 w-9 place-items-center rounded-xl bg-info/10 text-info">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h2 class="font-bold">History & Verification</h2>
                    <p class="text-xs text-base-content/50">
                        Historical information and data verification status.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-3">

            <div>
                <label for="founded_month" class="label">
                    <span class="label-text font-medium">Founded Month</span>
                </label>

                <select
                    id="founded_month"
                    name="founded_month"
                    class="select select-bordered w-full @error('founded_month') select-error @enderror"
                >
                    <option value="">Unknown</option>

                    @foreach([
                        1=>'January',
                        2=>'February',
                        3=>'March',
                        4=>'April',
                        5=>'May',
                        6=>'June',
                        7=>'July',
                        8=>'August',
                        9=>'September',
                        10=>'October',
                        11=>'November',
                        12=>'December'
                    ] as $month=>$monthName)

                        <option
                            value="{{ $month }}"
                            @selected((string)old('founded_month',$platform?->founded_month)===(string)$month)
                        >
                            {{ $monthName }}
                        </option>

                    @endforeach

                </select>

                @error('founded_month')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label for="founded_year" class="label">
                    <span class="label-text font-medium">Founded Year</span>
                </label>

                <input
                    id="founded_year"
                    type="number"
                    name="founded_year"
                    min="1800"
                    max="{{ date('Y') }}"
                    value="{{ old('founded_year',$platform?->founded_year) }}"
                    class="input input-bordered w-full @error('founded_year') input-error @enderror"
                    placeholder="{{ date('Y') }}"
                >

                @error('founded_year')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label for="last_verified_at" class="label">
                    <span class="label-text font-medium">Last Verified</span>
                </label>

                <input
                    id="last_verified_at"
                    type="datetime-local"
                    name="last_verified_at"
                    value="{{ old('last_verified_at',$platform?->last_verified_at?->format('Y-m-d\TH:i')) }}"
                    class="input input-bordered w-full @error('last_verified_at') input-error @enderror"
                >

                @error('last_verified_at')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </section>


    {{-- Submit --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">

        <a
            href="{{ $isEdit ? route('admin.platforms.show',$platform) : route('admin.platforms.index') }}"
            class="btn btn-ghost"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Cancel
        </a>

        <button
            type="submit"
            class="btn btn-primary gap-2 px-6"
        >
            <i class="fa-solid {{ $isEdit ? 'fa-floppy-disk' : 'fa-plus' }}"></i>

            {{ $isEdit ? 'Save Changes' : 'Create Platform' }}
        </button>

    </div>

</div>