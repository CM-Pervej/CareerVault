@csrf

<div class="space-y-8">

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>

            <div>
                <h3 class="font-bold">Please fix the following errors:</h3>

                <ul class="list-disc list-inside mt-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- ========================= --}}
    {{-- Company Information --}}
    {{-- ========================= --}}

    <div class="card bg-base-100 shadow border border-base-300">

        <div class="card-body">

            <h2 class="card-title mb-4">

                Company Information

            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- Name --}}
                <div class="lg:col-span-2">

                    <label class="label">
                        <span class="label-text font-medium">
                            Company Name <span class="text-error">*</span>
                        </span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $company->name ?? '') }}"
                        class="input input-bordered w-full @error('name') input-error @enderror"
                        placeholder="Google LLC">

                </div>

                {{-- Website --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Website
                        </span>
                    </label>

                    <input
                        type="url"
                        name="website"
                        value="{{ old('website', $company->website ?? '') }}"
                        class="input input-bordered w-full"
                        placeholder="https://company.com">

                </div>

                {{-- Career Page --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Career Page
                        </span>
                    </label>

                    <input
                        type="url"
                        name="career_page"
                        value="{{ old('career_page', $company->career_page ?? '') }}"
                        class="input input-bordered w-full"
                        placeholder="https://company.com/careers">

                </div>

                {{-- HR Email --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Email
                        </span>
                    </label>

                    <input
                        type="email"
                        name="hr_email"
                        value="{{ old('hr_email', $company->hr_email ?? '') }}"
                        class="input input-bordered w-full"
                        placeholder="hr@company.com">

                </div>

                {{-- Phone --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Phone
                        </span>
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $company->phone ?? '') }}"
                        class="input input-bordered w-full"
                        placeholder="+880...">

                </div>

                {{-- Industry --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Industry
                        </span>
                    </label>

                    <input
                        type="text"
                        name="industry"
                        value="{{ old('industry', $company->industry ?? '') }}"
                        class="input input-bordered w-full"
                        placeholder="Software Development">

                </div>

                {{-- Country --}}
                <div>

                    <label class="label">
                        <span class="label-text">
                            Country
                        </span>
                    </label>

                    <input
                        type="text"
                        name="country"
                        value="{{ old('country', $company->country ?? 'Bangladesh') }}"
                        class="input input-bordered w-full">

                </div>

                {{-- Address --}}
                <div class="lg:col-span-2">

                    <label class="label">
                        <span class="label-text">
                            Address
                        </span>
                    </label>

                    <textarea
                        name="address"
                        rows="3"
                        class="textarea textarea-bordered w-full"
                        placeholder="Company Address">{{ old('address', $company->address ?? '') }}</textarea>

                </div>

            </div>

        </div>

    </div>

    {{-- ========================= --}}
    {{-- Social Links --}}
    {{-- ========================= --}}

    <div class="card bg-base-100 shadow border border-base-300">

        <div class="card-body">

            <div class="flex items-center justify-between mb-5">

                <h2 class="card-title">

                    Social Links

                </h2>

                <button
                    type="button"
                    id="addSocial"
                    class="btn btn-primary btn-sm">

                    <i class="fa-solid fa-plus"></i>

                    Add Social Link

                </button>

            </div>

            <div
                id="socialContainer"
                class="space-y-4">

                @php
                    $links = old('social_links', $company->social_links ?? []);
                @endphp

                @foreach($links as $index => $link)

                    <div class="social-row grid grid-cols-1 lg:grid-cols-12 gap-4">

                        <div class="lg:col-span-4">

                            <input
                                type="text"
                                name="social_links[{{ $index }}][platform]"
                                value="{{ $link['platform'] ?? '' }}"
                                class="input input-bordered w-full"
                                placeholder="LinkedIn">

                        </div>

                        <div class="lg:col-span-7">

                            <input
                                type="url"
                                name="social_links[{{ $index }}][url]"
                                value="{{ $link['url'] ?? '' }}"
                                class="input input-bordered w-full"
                                placeholder="https://linkedin.com/company/...">

                        </div>

                        <div class="lg:col-span-1">

                            <button
                                type="button"
                                class="btn btn-error btn-square removeSocial">

                                <i class="fa-solid fa-trash"></i>

                            </button>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

    {{-- Buttons --}}
    <div class="flex justify-end gap-3">

        <a
            href="{{ route('companies.index') }}"
            class="btn btn-ghost">

            Cancel

        </a>

        <button
            type="submit"
            class="btn btn-primary">

            {{ isset($company) ? 'Update Company' : 'Save Company' }}

        </button>

    </div>

</div>