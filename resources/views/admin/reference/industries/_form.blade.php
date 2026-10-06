@php
    $editing = isset($industry);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-5">
        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-base">
                    <i class="fa-solid fa-layer-group text-primary"></i>
                    Industry Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Industry Name</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $industry->name ?? '') }}"
                            class="input input-bordered w-full @error('name') input-error @enderror"
                            placeholder="e.g. Software & Technology"
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
                            <span class="label-text font-semibold">Parent Industry</span>
                        </label>

                        <select name="parent_id" class="select select-bordered w-full @error('parent_id') select-error @enderror">
                            <option value="">Top-level Industry</option>

                            @foreach($parents as $parent)
                                <option
                                    value="{{ $parent->id }}"
                                    @selected(old('parent_id', $industry->parent_id ?? '') == $parent->id)
                                >
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('parent_id')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Sort Order</span>
                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            min="0"
                            value="{{ old('sort_order', $industry->sort_order ?? 0) }}"
                            class="input input-bordered w-full"
                        >
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Icon</span>
                        </label>

                        <input
                            type="text"
                            name="icon"
                            value="{{ old('icon', $industry->icon ?? '') }}"
                            class="input input-bordered w-full"
                            placeholder="fa-solid fa-code"
                        >
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Color</span>
                        </label>

                        <input
                            type="text"
                            name="color"
                            value="{{ old('color', $industry->color ?? '') }}"
                            class="input input-bordered w-full"
                            placeholder="#6366f1"
                        >
                    </div>
                </div>

                <div class="form-control mt-2">
                    <label class="label">
                        <span class="label-text font-semibold">Description</span>
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="textarea textarea-bordered w-full @error('description') textarea-error @enderror"
                        placeholder="Describe this industry..."
                    >{{ old('description', $industry->description ?? '') }}</textarea>

                    @error('description')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-base">
                    <i class="fa-solid fa-sliders text-primary"></i>
                    Settings
                </h2>

                <label class="label cursor-pointer justify-start gap-3 mt-3">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="toggle toggle-primary"
                        @checked(old('is_active', $industry->is_active ?? true))
                    >
                    <span>
                        <span class="font-semibold block">Active</span>
                        <span class="text-xs opacity-60">Available for company classification</span>
                    </span>
                </label>

                <div class="divider my-2"></div>

                <div class="text-xs opacity-60 leading-5">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Leave <strong>Parent Industry</strong> empty when creating a top-level industry.
                </div>
            </div>
        </div>
    </div>
</div>