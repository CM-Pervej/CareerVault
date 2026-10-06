@php
    $editing = isset($state);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    <div class="lg:col-span-2">
        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">

                <h2 class="card-title text-base">
                    <i class="fa-solid fa-map-location-dot text-primary"></i>
                    State / Division Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">

                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text font-semibold">Name</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $state->name ?? '') }}"
                            class="input input-bordered w-full @error('name') input-error @enderror"
                            placeholder="e.g. Dhaka"
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
                            <span class="label-text font-semibold">Code</span>
                        </label>

                        <input
                            type="text"
                            name="code"
                            value="{{ old('code', $state->code ?? '') }}"
                            class="input input-bordered w-full"
                            placeholder="e.g. BD-C"
                        >
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Type</span>
                        </label>

                        <input
                            type="text"
                            name="type"
                            list="state-types"
                            value="{{ old('type', $state->type ?? '') }}"
                            class="input input-bordered w-full"
                            placeholder="Division / State / Province"
                        >

                        <datalist id="state-types">
                            @foreach($types as $type)
                                <option value="{{ $type }}">
                            @endforeach
                            <option value="Division">
                            <option value="State">
                            <option value="Province">
                            <option value="Emirate">
                            <option value="Federal Territory">
                            <option value="Territory">
                            <option value="Region">
                            <option value="Country">
                            <option value="Union Territory">
                        </datalist>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Sort Order</span>
                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            min="0"
                            value="{{ old('sort_order', $state->sort_order ?? 0) }}"
                            class="input input-bordered w-full"
                        >
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div>
        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">

                <h2 class="card-title text-base">
                    <i class="fa-solid fa-globe text-primary"></i>
                    Country
                </h2>

                <div class="flex items-center gap-3 mt-3">
                    <div class="w-11 h-11 rounded-xl bg-base-200 flex items-center justify-center">
                        <i class="fa-solid fa-earth-asia"></i>
                    </div>

                    <div>
                        <div class="font-bold">
                            {{ $country->name }}
                        </div>

                        <div class="text-xs opacity-50">
                            {{ $country->iso2 }} · {{ $country->iso3 }}
                        </div>
                    </div>
                </div>

                <div class="divider my-3"></div>

                <label class="label cursor-pointer justify-start gap-3">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="toggle toggle-primary"
                        @checked(old('is_active', $state->is_active ?? true))
                    >

                    <span>
                        <span class="font-semibold block">Active</span>
                        <span class="text-xs opacity-60">
                            Available for city classification
                        </span>
                    </span>
                </label>

            </div>
        </div>
    </div>
</div>