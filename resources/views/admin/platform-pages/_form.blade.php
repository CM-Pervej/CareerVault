@php
    $editing = isset($platformPage);
@endphp

<div class="space-y-8 mt-10">
    {{-- Page Information --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <div class="flex items-start justify-between gap-4">
                <h2 class="text-base font-semibold text-base-content">Page details</h2>
                <span class="badge badge-ghost gap-1.5 border-base-300 text-xs font-medium text-base-content/60">
                    <i class="fa-solid fa-file-lines text-[11px]"></i> {{ $editing ? 'Editing' : 'New page' }}
                </span>
            </div>
            <p class="mt-0.5 text-sm text-base-content/60">What this page is, and where it lives.</p>
        </div>

        <div class="space-y-6 p-6">
            {{-- Platform + Name --}}
            <div class="grid gap-5 md:grid-cols-2">
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Platform <span class="text-error">*</span>
                        </span>
                    </label>

                    <select name="platform_id" class="select select-bordered w-full" required>
                        <option value="">Select platform</option>

                        @foreach($platforms as $platform)
                            <option value="{{ $platform->id }}" @selected(old('platform_id', $platformPage->platform_id ?? ($selectedPlatform->id ?? '')) == $platform->id)>
                                {{ $platform->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('platform_id')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Page name <span class="text-error">*</span>
                        </span>
                    </label>

                    <input type="text" name="name" value="{{ old('name', $platformPage->name ?? '') }}" class="input input-bordered w-full" placeholder="e.g. Jobs" required>

                    @error('name')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>

            {{-- Page Type + URL --}}
            <div class="grid gap-5 md:grid-cols-2">
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">Page type</span>
                    </label>

                    <input type="text" name="page_type" value="{{ old('page_type', $platformPage->page_type ?? '') }}" class="input input-bordered w-full" placeholder="e.g. Jobs, Help, Pricing" list="page-types">

                    <datalist id="page-types">
                        <option value="Jobs">
                        <option value="Career">
                        <option value="Pricing">
                        <option value="Help">
                        <option value="About">
                        <option value="Employer">
                        <option value="Job Seeker">
                        <option value="Terms">
                        <option value="Privacy">
                        <option value="Support">
                        <option value="Other">
                    </datalist>

                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-base-content/50">Used to group similar pages across platforms</span>
                    </label>

                    @error('page_type')
                        <label class="label pb-0 pt-0">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Official URL <span class="text-error">*</span>
                        </span>
                    </label>

                    <label class="input input-bordered flex w-full items-center gap-2">
                        <i class="fa-solid fa-link text-xs text-base-content/40"></i>
                        <input type="url" name="url" value="{{ old('url', $platformPage->url ?? '') }}" class="grow" placeholder="https://example.com/jobs" required>
                    </label>

                    @if($editing && $platformPage->url)
                        <label class="label pb-0 pt-1.5">
                            <a href="{{ $platformPage->url }}" target="_blank" rel="noopener"
                               class="label-text-alt link link-hover inline-flex items-center gap-1 text-base-content/50">
                                Open link
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        </label>
                    @endif

                    @error('url')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>

            <div class="divider my-0"></div>

            {{-- Short Description --}}
            <div class="form-control">
                <label class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Short description</span>
                </label>

                <input type="text" name="short_desc" value="{{ old('short_desc', $platformPage->short_desc ?? '') }}" class="input input-bordered w-full" placeholder="A one-line summary shown in lists">

                @error('short_desc')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Description --}}
            <div class="form-control">
                <label class="label pb-1.5 pt-0">
                    <span class="label-text font-medium">Description</span>
                </label>

                <textarea name="description" rows="5" class="textarea textarea-bordered w-full" placeholder="Describe what this page is used for...">
                    {{ old('description', $platformPage->description ?? '') }}
                </textarea>

                @error('description')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>
        </div>
    </section>

    {{-- Organization --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <h2 class="text-base font-semibold text-base-content">Visibility &amp; tracking</h2>
            <p class="mt-0.5 text-sm text-base-content/60">Control where this page shows up and how fresh it is.</p>
        </div>

        <div class="space-y-5 p-6">
            <div class="grid gap-5 md:grid-cols-2">
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">Sort order</span>
                    </label>

                    <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $platformPage->sort_order ?? 0) }}" class="input input-bordered w-full">

                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-base-content/50">Lower numbers appear first</span>
                    </label>

                    @error('sort_order')
                        <label class="label pb-0 pt-0">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">Last verified</span>
                    </label>

                    <input type="datetime-local" name="last_verified_at" value="{{ old('last_verified_at', isset($platformPage->last_verified_at) ? $platformPage->last_verified_at->format('Y-m-d\TH:i') : '') }}" class="input input-bordered w-full">

                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-base-content/50">When someone last confirmed this link works</span>
                    </label>

                    @error('last_verified_at')
                        <label class="label pb-0 pt-0">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>

            {{-- Active --}}
            @php
                $isActive = old('is_active', $platformPage->is_active ?? true);
            @endphp

            <label class="flex flex-col cursor-pointer gap-1 rounded-xl border border-base-300 p-3 transition-colors hover:border-base-content/20">
                <input type="hidden" name="is_active" value="0">

                <div class="flex justify-between items-center">
                    <div class="font-medium text-base-content">Active page</div>
                    
                    <div class="flex items-center gap-3">
                        <span class="badge {{ $isActive ? 'badge-success' : 'badge-ghost border-base-300' }} gap-1.5 text-xs font-medium">
                            <span class="h-1.5 w-1.5 rounded-full {{ $isActive ? 'bg-success-content' : 'bg-base-content/40' }}"></span> {{ $isActive ? 'Live' : 'Hidden' }}
                        </span>
    
                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary"
                            @checked($isActive)
                            onchange="this.closest('label').querySelector('.badge').outerHTML = this.checked
                                ? '<span class=\'badge badge-success gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-success-content\'></span>Live</span>'
                                : '<span class=\'badge badge-ghost border-base-300 gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-base-content/40\'></span>Hidden</span>';">
                    </div>
                </div>
                <div class="mt-0.5 text-sm text-base-content/60">Active pages can be displayed throughout CareerVault.</div>
            </label>
        </div>
    </section>
</div>

{{-- Actions --}}
<div class="sticky bottom-0 -mx-6 mt-8 flex flex-wrap justify-end gap-3 border-t border-base-300 bg-base-100/95 px-6 py-4 backdrop-blur">
    <a href="{{ $editing ? route('admin.platform-pages.show', $platformPage) : route('admin.platform-pages.index') }}" class="btn btn-ghost">Cancel</a>

    <button type="submit" class="btn btn-primary gap-2">
        <i class="fa-solid fa-floppy-disk"></i> {{ $editing ? 'Update page' : 'Create page' }}
    </button>
</div>