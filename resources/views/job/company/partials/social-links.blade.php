@php
    $socialLinks = old(
        'social_links',
        $company?->social_links ?? [
            ['platform' => '', 'url' => '',],
        ]
    );

    $platforms = [
        'LinkedIn', 'Facebook', 'X (Twitter)', 'GitHub', 'Instagram', 'YouTube',
        'TikTok', 'Discord', 'Telegram', 'Pinterest', 'Reddit', 'Medium',
        'Behance', 'Dribbble', 'Website', 'Other',
    ];
@endphp

<div class="card bg-base-100 shadow-xl border border-base-300/60 rounded-2xl">
    <div class="card-body p-6 lg:p-8">
        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 pb-6 border-b border-base-300/60">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/15 to-primary/5 flex items-center justify-center ring-1 ring-primary/10">
                    <i class="fa-solid fa-share-nodes text-primary text-lg"></i>
                </div>

                <div>
                    <h2 class="card-title text-base-content text-lg">Social Links</h2>
                    <p class="text-sm text-base-content/60 mt-0.5">Add company social media and website profiles.</p>
                </div>
            </div>

            <button type="button" id="add-social-link" class="btn btn-primary btn-sm rounded-lg shadow-sm">
                <i class="fa-solid fa-plus text-xs"></i> Add Link
            </button>
        </div>

        {{-- Items --}}
        <div id="social-links-container" class="flex flex-col gap-3">
            @foreach ($socialLinks as $index => $social)
                <div class="social-link-item border border-base-300/70 rounded-xl bg-base-100 hover:border-base-300 hover:shadow-sm transition-all">
                    <div class="flex flex-col lg:flex-row lg:items-end gap-4 p-4">
                        {{-- Order badge --}}
                        <div class="hidden lg:flex items-center justify-center w-7 h-7 rounded-full bg-base-200 text-base-content/50 text-xs font-semibold shrink-0 mb-2.5">
                            {{ $index + 1 }}
                        </div>

                        {{-- Platform --}}
                        <div class="form-control w-full lg:w-44 shrink-0">
                            <label class="label pb-1.5">
                                <span class="label-text text-xs font-medium text-base-content/70">Platform</span>
                            </label>

                            <select name="social_links[{{ $index }}][platform]" class="select select-bordered select-sm w-full social-platform focus:select-primary @error("social_links.$index.platform") select-error @enderror">
                                <option value="">Select platform</option>

                                @foreach ($platforms as $platform)
                                    <option value="{{ $platform }}" @selected(($social['platform'] ?? '') === $platform)> {{ $platform }} </option>
                                @endforeach
                            </select>

                            @error("social_links.$index.platform")
                                <label class="label pt-1.5">
                                    <span class="label-text-alt text-error flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                        {{ $message }}
                                    </span>
                                </label>
                            @enderror
                        </div>

                        {{-- URL --}}
                        <div class="form-control w-full flex-1">
                            <label class="label pb-1.5">
                                <span class="label-text text-xs font-medium text-base-content/70">URL</span>
                            </label>

                            <label class="input input-bordered input-sm flex items-center gap-2 w-full focus-within:input-primary @error("social_links.$index.url") input-error @enderror">
                                <i class="fa-solid fa-link text-base-content/30 text-xs"></i>
                                <input type="url" name="social_links[{{ $index }}][url]" value="{{ $social['url'] ?? '' }}" placeholder="https://linkedin.com/company/example" class="grow social-url">
                            </label>

                            @error("social_links.$index.url")
                                <label class="label pt-1.5">
                                    <span class="label-text-alt text-error flex items-center gap-1">
                                        <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                                        {{ $message }}
                                    </span>
                                </label>
                            @enderror
                        </div>

                        {{-- Remove --}}
                        <div class="flex justify-end lg:justify-center lg:mb-1 shrink-0">
                            <div class="tooltip tooltip-left" data-tip="At least one social link is required">
                                <button type="button" class="btn btn-ghost btn-sm btn-square text-base-content/40 hover:bg-error/10 hover:text-error remove-social-link disabled:opacity-30" @disabled($loop->count === 1)>
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center">
            {{-- Footer count --}}
            <div class="flex items-center gap-2 mt-4 text-xs text-base-content/40">
                <i class="fa-solid fa-circle-info text-[10px]"></i>
                <span id="social-links-count-label">{{ count($socialLinks) }} {{ Str::plural('link', count($socialLinks)) }} added</span>
            </div>
            <div class="badge badge-ghost badge-sm gap-1.5 hidden sm:flex">
                <i class="fa-solid fa-circle-info text-[10px]"></i> Step 6 of 6
            </div>
        </div>
    </div>
</div>

{{-- Social Link Template --}}
<template id="social-link-template">
    <div class="social-link-item border border-base-300/70 rounded-xl bg-base-100 hover:border-base-300 hover:shadow-sm transition-all">
        <div class="flex flex-col lg:flex-row lg:items-end gap-4 p-4">
            <div class="hidden lg:flex items-center justify-center w-7 h-7 rounded-full bg-base-200 text-base-content/50 text-xs font-semibold shrink-0 mb-2.5 social-link-order-badge">
                #
            </div>

            <div class="form-control w-full lg:w-44 shrink-0">
                <label class="label pb-1.5">
                    <span class="label-text text-xs font-medium text-base-content/70">Platform</span>
                </label>

                <select class="select select-bordered select-sm w-full social-platform focus:select-primary">
                    <option value="">Select platform</option>

                    @foreach ($platforms as $platform)
                        <option value="{{ $platform }}"> {{ $platform }} </option>
                    @endforeach
                </select>
            </div>

            <div class="form-control w-full flex-1">
                <label class="label pb-1.5">
                    <span class="label-text text-xs font-medium text-base-content/70">URL</span>
                </label>

                <label class="input input-bordered input-sm flex items-center gap-2 w-full focus-within:input-primary">
                    <i class="fa-solid fa-link text-base-content/30 text-xs"></i>
                    <input type="url" class="grow social-url" placeholder="https://linkedin.com/company/example">
                </label>
            </div>

            <div class="flex justify-end lg:justify-center lg:mb-1 shrink-0">
                <div class="tooltip tooltip-left" data-tip="At least one social link is required">
                    <button type="button" class="btn btn-ghost btn-sm btn-square text-base-content/40 hover:bg-error/10 hover:text-error remove-social-link">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>