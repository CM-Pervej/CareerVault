@php
    $editing = isset($platformPage);
@endphp

<div class="space-y-6">
    {{-- Page Information --}}
    <section class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-bold">Page Information</h2>
            <p class="mt-1 text-sm text-base-content/60">Define the official page and its destination.</p>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">
            {{-- Platform --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">
                        Platform <span class="text-error">*</span>
                    </span>
                </label>

                <select  name="platform_id"  class="select select-bordered w-full"  required>
                    <option value="">Select platform</option>

                    @foreach($platforms as $platform)
                        <option value="{{ $platform->id }}"
                            @selected(
                                old('platform_id', $platformPage->platform_id?? ($selectedPlatform->id ?? '')) == $platform->id
                            )>
                            {{ $platform->name }}
                        </option>
                    @endforeach
                </select>

                @error('platform_id')
                    <label class="label">
                        <span class="label-text-alt text-error"> {{ $message }} </span>
                    </label>
                @enderror
            </div>

            {{-- Name --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">
                        Page Name <span class="text-error">*</span>
                    </span>
                </label>

                <input type="text" name="name" value="{{ old('name', $platformPage->name ?? '') }}" class="input input-bordered w-full" placeholder="e.g. Jobs" required>

                @error('name')
                    <label class="label">
                        <span class="label-text-alt text-error"> {{ $message }} </span>
                    </label>
                @enderror
            </div>

            {{-- Page Type --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Page Type</span>
                </label>

                <input type="text" name="page_type" value="{{ old('page_type', $platformPage->page_type ?? '') }}" class="input input-bordered w-full" placeholder="e.g. Jobs, Help, Pricing, About" list="page-types">

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

                @error('page_type')
                    <label class="label">
                        <span class="label-text-alt text-error"> {{ $message }} </span>
                    </label>
                @enderror
            </div>

            {{-- URL --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">
                        Official URL <span class="text-error">*</span>
                    </span>
                </label>

                <input type="url" name="url" value="{{ old('url', $platformPage->url ?? '') }}" class="input input-bordered w-full" placeholder="https://example.com/jobs" required>

                @error('url')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            {{-- Short Description --}}
            <div class="form-control md:col-span-2">
                <label class="label">
                    <span class="label-text font-semibold">Short Description</span>
                </label>

                <input type="text" name="short_desc" value="{{ old('short_desc', $platformPage->short_desc ?? '') }}" class="input input-bordered w-full" placeholder="A concise explanation of this page">

                @error('short_desc')
                    <label class="label">
                        <span class="label-text-alt text-error"> {{ $message }} </span>
                    </label>
                @enderror
            </div>

            {{-- Description --}}
            <div class="form-control md:col-span-2">
                <label class="label">
                    <span class="label-text font-semibold">Description</span>
                </label>

                <textarea name="description" rows="6" class="textarea textarea-bordered w-full" placeholder="Describe what this page is used for...">{{ old('description', $platformPage->description ?? '') }}</textarea>

                @error('description')
                    <label class="label">
                        <span class="label-text-alt text-error"> {{ $message }} </span>
                    </label>
                @enderror
            </div>
        </div>
    </section>

    {{-- Organization --}}
    <section class="rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="border-b border-base-300 px-5 py-4">
            <h2 class="font-bold">Organization</h2>
            <p class="mt-1 text-sm text-base-content/60">Control visibility, ordering, and verification.</p>
        </div>

        <div class="grid gap-5 p-5 md:grid-cols-2">
            {{-- Sort Order --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Sort Order</span>
                </label>

                <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $platformPage->sort_order ?? 0) }}" class="input input-bordered w-full">

                @error('sort_order')
                    <label class="label">
                        <span class="label-text-alt text-error"> {{ $message }} </span>
                    </label>
                @enderror
            </div>

            {{-- Last Verified --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Last Verified</span>
                </label>

                <input type="datetime-local" name="last_verified_at" value="{{ old('last_verified_at', isset($platformPage->last_verified_at)? $platformPage->last_verified_at->format('Y-m-d\TH:i'): '') }}" class="input input-bordered w-full">

                @error('last_verified_at')
                    <label class="label">
                        <span class="label-text-alt text-error"> {{ $message }} </span>
                    </label>
                @enderror
            </div>

            {{-- Active --}}
            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-base-300 p-4 md:col-span-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary" @checked(old('is_active', $platformPage->is_active ?? true))>
                
                <div>
                    <div class="font-semibold">Active page</div>
                    <div class="text-sm text-base-content/60">Active pages can be displayed throughout CareerVault.</div>
                </div>
            </label>
        </div>
    </section>
</div>

{{-- Actions --}}
<div class="flex flex-wrap justify-end gap-3 pt-2">
    <a href="{{ $editing? route('admin.platform-pages.show', $platformPage): route('admin.platform-pages.index')}}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary gap-2">
        <i class="fa-solid fa-floppy-disk"></i> {{ $editing ? 'Update Page' : 'Create Page' }}
    </button>
</div>