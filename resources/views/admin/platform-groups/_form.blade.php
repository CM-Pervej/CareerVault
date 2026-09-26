@php
    $editing = isset($platformGroup);
    $selectedPlatformId = old(
        'platform_id',
        $platformGroup->platform_id ?? $platform?->id
    );

    $accessType = old(
        'access_type',
        $platformGroup->access_type ?? 'public'
    );
@endphp

<div class="space-y-8 mt-10">
    {{-- Group Information --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-base-content">Group details</h2>
                    <p class="mt-0.5 text-sm text-base-content/60">What this group is, and where it lives.</p>
                </div>

                <span class="badge badge-ghost gap-1.5 border-base-300 text-xs font-medium text-base-content/60">
                    <i class="fa-solid fa-users text-[11px]"></i>
                    {{ $editing ? 'Editing' : 'New group' }}
                </span>
            </div>
        </div>

        <div class="space-y-6 p-6">
            {{-- Platform + Name --}}
            <div class="grid gap-5 md:grid-cols-2">
                {{-- Platform --}}
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Platform <span class="text-error">*</span>
                        </span>
                    </label>

                    <select name="platform_id" class="select select-bordered w-full @error('platform_id') select-error @enderror" required>
                        <option value="">Select platform</option>

                        @foreach($platforms as $item)
                            <option value="{{ $item->id }}" @selected((string) $selectedPlatformId === (string) $item->id)>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('platform_id')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Name --}}
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Group name <span class="text-error">*</span>
                        </span>
                    </label>

                    <input type="text" name="name" value="{{ old('name', $platformGroup->name ?? '') }}" class="input input-bordered w-full @error('name') input-error @enderror" placeholder="e.g. Laravel Bangladesh" required>

                    @error('name')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>

            {{-- Group Type + URL --}}
            <div class="grid gap-5 md:grid-cols-2">
                {{-- Group Type --}}
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">Group type</span>
                    </label>

                    <input type="text" name="group_type" value="{{ old('group_type', $platformGroup->group_type ?? '') }}" class="input input-bordered w-full @error('group_type') input-error @enderror" placeholder="e.g. Job Group, Community, Professional">

                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-base-content/50">Used to group similar communities across platforms</span>
                    </label>

                    @error('group_type')
                        <label class="label pb-0 pt-0">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- URL --}}
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Group URL <span class="text-error">*</span>
                        </span>
                    </label>

                    <label class="input input-bordered flex w-full items-center gap-2 @error('url') input-error @enderror">
                        <i class="fa-solid fa-link text-xs text-base-content/40"></i>
                        <input type="url" name="url" value="{{ old('url', $platformGroup->url ?? '') }}" class="grow" placeholder="https://example.com/group" required>
                    </label>

                    @if($editing && $platformGroup->url)
                        <label class="label pb-0 pt-1.5">
                            <a href="{{ $platformGroup->url }}" target="_blank" rel="noopener" class="label-text-alt link link-hover inline-flex items-center gap-1 text-base-content/50">
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

                <textarea name="short_desc" rows="2" maxlength="500" class="textarea textarea-bordered w-full @error('short_desc') textarea-error @enderror" placeholder="A short summary shown in lists and cards.">{{ old('short_desc', $platformGroup->short_desc ?? '') }}</textarea>

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

                <textarea name="description" rows="5" class="textarea textarea-bordered w-full @error('description') textarea-error @enderror" placeholder="Describe the group, its purpose, audience, and what members can find there.">{{ old('description', $platformGroup->description ?? '') }}</textarea>

                @error('description')
                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>
        </div>
    </section>

    {{-- Access & Discovery --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <h2 class="text-base font-semibold text-base-content">Access &amp; discovery</h2>
            <p class="mt-0.5 text-sm text-base-content/60">Define how the group can be accessed and discovered.</p>
        </div>

        <div class="space-y-5 p-6">
            <div class="grid gap-5 md:grid-cols-2">
                {{-- Access Type --}}
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Access type <span class="text-error">*</span>
                        </span>
                    </label>

                    <select name="access_type" class="select select-bordered w-full @error('access_type') select-error @enderror" required>
                        <option value="public" @selected($accessType === 'public')>Public</option>
                        <option value="members_only" @selected($accessType === 'members_only')>Members Only</option>
                        <option value="private" @selected($accessType === 'private')>Private</option>
                    </select>

                    @error('access_type')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Bangladesh Focus --}}
                @php
                    $isBangladeshFocused = old(
                        'is_bangladesh_focused',
                        $platformGroup->is_bangladesh_focused ?? false
                    );
                @endphp

                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">Bangladesh focus</span>
                    </label>

                    <label class="flex cursor-pointer flex-col gap-1 rounded-xl border border-base-300 p-3 transition-colors hover:border-base-content/20">
                        <div class="flex items-center justify-between gap-3">
                            <div class="font-medium text-base-content">Bangladesh focused</div>

                            <div class="flex items-center gap-3">
                                <span class="badge {{ $isBangladeshFocused ? 'badge-success' : 'badge-ghost border-base-300' }} gap-1.5 text-xs font-medium">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $isBangladeshFocused ? 'bg-success-content' : 'bg-base-content/40' }}"></span>
                                    {{ $isBangladeshFocused ? 'Focused' : 'General' }}
                                </span>

                                <input type="checkbox" name="is_bangladesh_focused" value="1" class="checkbox checkbox-primary" @checked($isBangladeshFocused)
                                    onchange="this.closest('label').querySelector('.badge').outerHTML = this.checked
                                        ? '<span class=\'badge badge-success gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-success-content\'></span>Focused</span>'
                                        : '<span class=\'badge badge-ghost border-base-300 gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-base-content/40\'></span>General</span>';">
                            </div>
                        </div>

                        <div class="mt-0.5 text-sm text-base-content/60">
                            Mark this group as primarily relevant to Bangladesh.
                        </div>
                    </label>

                    @error('is_bangladesh_focused')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>
    </section>

    {{-- Media --}}
    <div class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex size-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                    <i class="fa-solid fa-images"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-base-content">Media</h2>
                    <p class="mt-1 text-sm text-base-content/60">
                        Upload a logo and cover image for this group.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2">

            {{-- Logo --}}
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium">Logo</span>
                    <span class="label-text-alt text-base-content/50">JPG, PNG, WEBP</span>
                </label>

                <div class="rounded-lg border border-base-300 bg-base-200/30 p-4">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

                        <div class="relative shrink-0">
                            <div id="logo-preview-container"
                                class="flex size-24 items-center justify-center overflow-hidden rounded-xl border border-base-300 bg-base-100">
                                @if($editing && $platformGroup->logo)
                                    <img id="logo-preview"
                                        src="{{ Storage::url($platformGroup->logo) }}"
                                        alt="{{ $platformGroup->name }}"
                                        class="size-full object-cover">
                                @else
                                    <div id="logo-placeholder" class="text-center text-base-content/30">
                                        <i class="fa-solid fa-image text-2xl"></i>
                                        <div class="mt-1 text-xs">No image</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="min-w-0 flex-1">
                            <input type="file"
                                name="logo"
                                id="logo"
                                accept="image/jpeg,image/png,image/webp"
                                class="file-input file-input-bordered w-full">

                            <p id="logo-file-name" class="mt-2 truncate text-xs text-base-content/50">
                                @if($editing && $platformGroup->logo)
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

                        <div id="cover-preview-container"
                            class="relative flex h-40 w-full items-center justify-center overflow-hidden rounded-xl border border-base-300 bg-base-100">
                            @if($editing && $platformGroup->cover_image)
                                <img id="cover-preview"
                                    src="{{ Storage::url($platformGroup->cover_image) }}"
                                    alt="{{ $platformGroup->name }} cover image"
                                    class="size-full object-cover">
                            @else
                                <div id="cover-placeholder" class="text-center text-base-content/30">
                                    <i class="fa-solid fa-image text-3xl"></i>
                                    <div class="mt-1 text-xs">No cover image</div>
                                </div>
                            @endif
                        </div>

                        <input type="file"
                            name="cover_image"
                            id="cover_image"
                            accept="image/jpeg,image/png,image/webp"
                            class="file-input file-input-bordered w-full">

                        <p id="cover-file-name" class="truncate text-xs text-base-content/50">
                            @if($editing && $platformGroup->cover_image)
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

    {{-- Publishing & Verification --}}
    <section class="overflow-hidden rounded-lg border border-gray-300 bg-base-100">
        <div class="border-b border-base-300 bg-base-200/40 px-6 py-5">
            <h2 class="text-base font-semibold text-base-content">Publishing &amp; verification</h2>
            <p class="mt-0.5 text-sm text-base-content/60">Control visibility, ordering, and verification.</p>
        </div>

        <div class="space-y-5 p-6">
            <div class="grid gap-5 md:grid-cols-3">
                {{-- Status --}}
                @php
                    $isActive = old(
                        'is_active',
                        $platformGroup->is_active ?? true
                    );
                @endphp

                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Status <span class="text-error">*</span>
                        </span>
                    </label>

                    <select name="is_active" class="select select-bordered w-full @error('is_active') select-error @enderror" required>
                        <option value="1" @selected($isActive)>Active</option>
                        <option value="0" @selected(!$isActive)>Inactive</option>
                    </select>

                    @error('is_active')
                        <label class="label pb-0 pt-1.5">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Sort Order --}}
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">
                            Sort order <span class="text-error">*</span>
                        </span>
                    </label>

                    <input type="number" name="sort_order" min="0" max="999999" value="{{ old('sort_order', $platformGroup->sort_order ?? 0) }}" class="input input-bordered w-full @error('sort_order') input-error @enderror" required>

                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-base-content/50">Lower numbers appear first</span>
                    </label>

                    @error('sort_order')
                        <label class="label pb-0 pt-0">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                {{-- Last Verified --}}
                <div class="form-control">
                    <label class="label pb-1.5 pt-0">
                        <span class="label-text font-medium">Last verified</span>
                    </label>

                    <input type="datetime-local" name="last_verified_at" value="{{ old(
                        'last_verified_at',
                        isset($platformGroup->last_verified_at)
                            ? $platformGroup->last_verified_at->format('Y-m-d\TH:i')
                            : ''
                    ) }}" class="input input-bordered w-full @error('last_verified_at') input-error @enderror">

                    <label class="label pb-0 pt-1.5">
                        <span class="label-text-alt text-base-content/50">When someone last confirmed this group works</span>
                    </label>

                    @error('last_verified_at')
                        <label class="label pb-0 pt-0">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>

            {{-- Active --}}
            <label class="flex cursor-pointer flex-col gap-1 rounded-xl border border-base-300 p-3 transition-colors hover:border-base-content/20">
                <div class="flex items-center justify-between gap-3">
                    <div class="font-medium text-base-content">Active group</div>

                    <div class="flex items-center gap-3">
                        <span class="badge {{ $isActive ? 'badge-success' : 'badge-ghost border-base-300' }} gap-1.5 text-xs font-medium">
                            <span class="h-1.5 w-1.5 rounded-full {{ $isActive ? 'bg-success-content' : 'bg-base-content/40' }}"></span>
                            {{ $isActive ? 'Live' : 'Hidden' }}
                        </span>

                        <input type="hidden" name="is_active" value="0">

                        <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary" @checked($isActive)
                            onchange="this.closest('label').querySelector('.badge').outerHTML = this.checked
                                ? '<span class=\'badge badge-success gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-success-content\'></span>Live</span>'
                                : '<span class=\'badge badge-ghost border-base-300 gap-1.5 text-xs font-medium\'><span class=\'h-1.5 w-1.5 rounded-full bg-base-content/40\'></span>Hidden</span>';">
                    </div>
                </div>

                <div class="mt-0.5 text-sm text-base-content/60">
                    Active groups can be displayed throughout CareerVault.
                </div>
            </label>
        </div>
    </section>
</div>

{{-- Actions --}}
<div class="sticky bottom-0 -mx-6 mt-8 flex flex-wrap justify-end gap-3 border-t border-base-300 bg-base-100/95 px-6 py-4 backdrop-blur">
    <a href="{{ $editing && $platform
        ? route('admin.platform-groups.show', $platformGroup)
        : route('admin.platform-groups.index', $platformSlug ? ['platform' => $platformSlug] : [])
    }}" class="btn btn-ghost">
        Cancel
    </a>

    <button type="submit" class="btn btn-primary gap-2">
        <i class="fa-solid fa-floppy-disk"></i>
        {{ $editing ? 'Update Group' : 'Create Group' }}
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