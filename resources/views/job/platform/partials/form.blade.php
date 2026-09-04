@php
    $platform = $platform ?? null;
@endphp

@if ($errors->any())
    <div class="alert alert-error mb-6">
        <div>
            <h3 class="font-bold">Please fix the following errors:</h3>
            <ul class="list-disc list-inside text-sm mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <div>
                        <h2 class="card-title">Basic Information</h2>
                        <p class="text-sm text-base-content/60">Main information about the platform.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-medium">Platform Name <span class="text-error">*</span></span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $platform?->name) }}"
                            class="input input-bordered w-full @error('name') input-error @enderror"
                            placeholder="e.g. Bdjobs.com"
                            required
                        >
                        @error('name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Official Name</span>
                        </label>
                        <input
                            type="text"
                            name="official_name"
                            value="{{ old('official_name', $platform?->official_name) }}"
                            class="input input-bordered w-full @error('official_name') input-error @enderror"
                            placeholder="Official registered name"
                        >
                        @error('official_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Short Description</span>
                        </label>
                        <input
                            type="text"
                            name="short_desc"
                            value="{{ old('short_desc', $platform?->short_desc) }}"
                            class="input input-bordered w-full @error('short_desc') input-error @enderror"
                            placeholder="Short platform description"
                        >
                        @error('short_desc')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-medium">Description</span>
                        </label>
                        <textarea
                            name="description"
                            rows="5"
                            class="textarea textarea-bordered w-full @error('description') textarea-error @enderror"
                            placeholder="Describe the platform, its purpose, users, services, and job opportunities..."
                        >{{ old('description', $platform?->description) }}</textarea>
                        @error('description')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-info/10 text-info flex items-center justify-center">
                        <i class="fa-solid fa-link"></i>
                    </div>
                    <div>
                        <h2 class="card-title">Platform URLs</h2>
                        <p class="text-sm text-base-content/60">Website and job listing URLs.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Base URL</span>
                        </label>
                        <input
                            type="url"
                            name="base_url"
                            value="{{ old('base_url', $platform?->base_url) }}"
                            class="input input-bordered w-full @error('base_url') input-error @enderror"
                            placeholder="https://example.com"
                        >
                        @error('base_url')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Job URL</span>
                        </label>
                        <input
                            type="url"
                            name="job_url"
                            value="{{ old('job_url', $platform?->job_url) }}"
                            class="input input-bordered w-full @error('job_url') input-error @enderror"
                            placeholder="https://example.com/jobs"
                        >
                        @error('job_url')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <div>
                        <h2 class="card-title">Appearance</h2>
                        <p class="text-sm text-base-content/60">Branding and visual information.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Icon</span>
                        </label>
                        <input
                            type="text"
                            name="icon"
                            value="{{ old('icon', $platform?->icon) }}"
                            class="input input-bordered w-full @error('icon') input-error @enderror"
                            placeholder="fa-solid fa-briefcase"
                        >
                        @error('icon')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Brand Color</span>
                        </label>
                        <div class="flex gap-2">
                            <input
                                type="color"
                                id="colorPicker"
                                value="{{ old('color', $platform?->color ?? '#2563EB') }}"
                                class="w-12 h-12 rounded-lg border border-base-300 cursor-pointer"
                            >
                            <input
                                type="text"
                                name="color"
                                id="colorInput"
                                value="{{ old('color', $platform?->color) }}"
                                class="input input-bordered w-full @error('color') input-error @enderror"
                                placeholder="#2563EB"
                            >
                        </div>
                        @error('color')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Logo</span>
                        </label>

                        <input
                            type="file"
                            name="logo"
                            id="logoInput"
                            accept="image/*"
                            class="file-input file-input-bordered w-full @error('logo') file-input-error @enderror"
                        >

                        <div id="logoPreviewContainer" class="mt-3">
                            @if($platform?->logo)
                                <img
                                    id="logoPreview"
                                    src="{{ asset('storage/' . $platform->logo) }}"
                                    alt="{{ $platform->name }}"
                                    class="w-24 h-24 rounded-xl object-contain border border-base-300 bg-base-200 p-2"
                                >
                                <p id="logoPreviewText" class="text-xs text-base-content/50 mt-2">
                                    Current logo
                                </p>
                            @else
                                <div
                                    id="logoPlaceholder"
                                    class="flex items-center justify-center w-24 h-24 rounded-xl border border-dashed border-base-300 bg-base-200"
                                >
                                    <div class="text-center text-base-content/40">
                                        <i class="fa-solid fa-image text-xl"></i>
                                        <p class="text-xs mt-1">No image</p>
                                    </div>
                                </div>

                                <img
                                    id="logoPreview"
                                    src=""
                                    alt="Logo preview"
                                    class="hidden w-24 h-24 rounded-xl object-contain border border-base-300 bg-base-200 p-2"
                                >

                                <p id="logoPreviewText" class="hidden text-xs text-base-content/50 mt-2">
                                    Selected logo
                                </p>
                            @endif
                        </div>

                        @error('logo')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Cover Image</span>
                        </label>

                        <input
                            type="file"
                            name="cover_image"
                            id="coverImageInput"
                            accept="image/*"
                            class="file-input file-input-bordered w-full @error('cover_image') file-input-error @enderror"
                        >

                        <div id="coverImagePreviewContainer" class="mt-3">
                            @if($platform?->cover_image)
                                <img
                                    id="coverImagePreview"
                                    src="{{ asset('storage/' . $platform->cover_image) }}"
                                    alt="{{ $platform->name }}"
                                    class="w-full h-40 rounded-xl object-cover border border-base-300"
                                >
                                <p id="coverImagePreviewText" class="text-xs text-base-content/50 mt-2">
                                    Current cover image
                                </p>
                            @else
                                <div
                                    id="coverImagePlaceholder"
                                    class="flex items-center justify-center w-full h-40 rounded-xl border border-dashed border-base-300 bg-base-200"
                                >
                                    <div class="text-center text-base-content/40">
                                        <i class="fa-solid fa-image text-2xl"></i>
                                        <p class="text-xs mt-1">No image</p>
                                    </div>
                                </div>

                                <img
                                    id="coverImagePreview"
                                    src=""
                                    alt="Cover image preview"
                                    class="hidden w-full h-40 rounded-xl object-cover border border-base-300"
                                >

                                <p id="coverImagePreviewText" class="hidden text-xs text-base-content/50 mt-2">
                                    Selected cover image
                                </p>
                            @endif
                        </div>

                        @error('cover_image')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-success/10 text-success flex items-center justify-center">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div>
                        <h2 class="card-title">Platform Details</h2>
                        <p class="text-sm text-base-content/60">Classification and platform history.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Job Type <span class="text-error">*</span></span>
                        </label>
                        <select name="job_type" class="select select-bordered w-full @error('job_type') select-error @enderror" required>
                            <option value="Both" @selected(old('job_type', $platform?->job_type) === 'Both')>Both</option>
                            <option value="Onsite" @selected(old('job_type', $platform?->job_type) === 'Onsite')>Onsite</option>
                            <option value="Remote" @selected(old('job_type', $platform?->job_type) === 'Remote')>Remote</option>
                        </select>
                        @error('job_type')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Business Model <span class="text-error">*</span></span>
                        </label>
                        <select name="business_model" class="select select-bordered w-full @error('business_model') select-error @enderror" required>
                            <option value="Free" @selected(old('business_model', $platform?->business_model) === 'Free')>Free</option>
                            <option value="Freemium" @selected(old('business_model', $platform?->business_model) === 'Freemium')>Freemium</option>
                            <option value="Paid" @selected(old('business_model', $platform?->business_model) === 'Paid')>Paid</option>
                        </select>
                        @error('business_model')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Founded Month</span>
                        </label>
                        <select name="founded_month" class="select select-bordered w-full @error('founded_month') select-error @enderror">
                            <option value="">Unknown</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" @selected((int) old('founded_month', $platform?->founded_month) === $month)>
                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                        @error('founded_month')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Founded Year</span>
                        </label>
                        <input
                            type="number"
                            name="founded_year"
                            value="{{ old('founded_year', $platform?->founded_year) }}"
                            min="1800"
                            max="{{ now()->year }}"
                            class="input input-bordered w-full @error('founded_year') input-error @enderror"
                            placeholder="2020"
                        >
                        @error('founded_year')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Sort Order <span class="text-error">*</span></span>
                        </label>
                        <input
                            type="number"
                            name="sort_order"
                            value="{{ old('sort_order', $platform?->sort_order ?? 0) }}"
                            min="0"
                            class="input input-bordered w-full @error('sort_order') input-error @enderror"
                            required
                        >
                        @error('sort_order')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Last Verified</span>
                        </label>
                        <input
                            type="date"
                            name="last_verified_at"
                            value="{{ old('last_verified_at', $platform?->last_verified_at?->format('Y-m-d')) }}"
                            class="input input-bordered w-full @error('last_verified_at') input-error @enderror"
                        >
                        @error('last_verified_at')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Platform Status</h2>

                <label class="flex items-center justify-between gap-4 p-3 rounded-xl hover:bg-base-200 transition-colors cursor-pointer">
                    <div>
                        <div class="font-medium">Active Platform</div>
                        <div class="text-xs text-base-content/60">Make this platform visible.</div>
                    </div>
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="toggle toggle-success"
                        @checked(old('is_active', $platform?->is_active ?? true))
                    >
                </label>

                <label class="flex items-center justify-between gap-4 p-3 rounded-xl hover:bg-base-200 transition-colors cursor-pointer">
                    <div>
                        <div class="font-medium">Account Required</div>
                        <div class="text-xs text-base-content/60">Users need an account to apply.</div>
                    </div>
                    <input
                        type="checkbox"
                        name="account_required"
                        value="1"
                        class="toggle toggle-primary"
                        @checked(old('account_required', $platform?->account_required ?? false))
                    >
                </label>

                <label class="flex items-center justify-between gap-4 p-3 rounded-xl hover:bg-base-200 transition-colors cursor-pointer">
                    <div>
                        <div class="font-medium">Bangladesh Focused</div>
                        <div class="text-xs text-base-content/60">Primarily focused on Bangladesh.</div>
                    </div>
                    <input
                        type="checkbox"
                        name="is_bangladesh_focused"
                        value="1"
                        class="toggle toggle-info"
                        @checked(old('is_bangladesh_focused', $platform?->is_bangladesh_focused ?? false))
                    >
                </label>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Generated Slug</h2>

                <div class="bg-base-200 rounded-xl p-4">
                    <div class="text-xs text-base-content/50 mb-1">Slug</div>
                    <div id="slugPreview" class="font-mono text-sm break-all">
                        {{ $platform?->slug ?? 'platform-slug' }}
                    </div>
                </div>

                <p class="text-xs text-base-content/50 mt-2">
                    The slug is generated automatically from the platform name.
                </p>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">Actions</h2>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fa-solid fa-check"></i>
                        {{ $platform ? 'Update Platform' : 'Create Platform' }}
                    </button>

                    <a href="{{ route('platforms.index') }}" class="btn btn-ghost w-full">
                        <i class="fa-solid fa-arrow-left"></i>
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const logoPlaceholder = document.getElementById('logoPlaceholder');
    const logoPreviewText = document.getElementById('logoPreviewText');

    logoInput?.addEventListener('change', function() {
        const file = this.files[0];

        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function(event) {
            logoPreview.src = event.target.result;
            logoPreview.classList.remove('hidden');
            logoPlaceholder?.classList.add('hidden');
            logoPreviewText?.classList.remove('hidden');
            logoPreviewText.textContent = 'Selected logo';
        };

        reader.readAsDataURL(file);
    });

    const coverImageInput = document.getElementById('coverImageInput');
    const coverImagePreview = document.getElementById('coverImagePreview');
    const coverImagePlaceholder = document.getElementById('coverImagePlaceholder');
    const coverImagePreviewText = document.getElementById('coverImagePreviewText');

    coverImageInput?.addEventListener('change', function() {
        const file = this.files[0];

        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function(event) {
            coverImagePreview.src = event.target.result;
            coverImagePreview.classList.remove('hidden');
            coverImagePlaceholder?.classList.add('hidden');
            coverImagePreviewText?.classList.remove('hidden');
            coverImagePreviewText.textContent = 'Selected cover image';
        };

        reader.readAsDataURL(file);
    });
</script>