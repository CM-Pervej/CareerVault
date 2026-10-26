@php
    $isEdit = isset($state);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    <x-admin.form.select
        name="country_id"
        label="Country"
        :value="$state->country_id ?? ''"
        placeholder="Select country"
        required
    >
        @foreach($countries as $country)
            <option
                value="{{ $country->id }}"
                @selected(old('country_id', $state->country_id ?? '') == $country->id)
            >
                {{ $country->name }}
            </option>
        @endforeach
    </x-admin.form.select>

    <x-admin.form.input
        name="name"
        label="State / Division Name"
        placeholder="e.g. Dhaka"
        :value="$state->name ?? ''"
        maxlength="100"
        required
    />

    <div>
        <x-admin.form.input
            name="type"
            label="Type"
            placeholder="e.g. Division, State, Province"
            :value="$state->type ?? ''"
            list="state-types"
            maxlength="50"
        />

        <datalist id="state-types">
            <option value="Division">
            <option value="State">
            <option value="Province">
            <option value="Emirate">
            <option value="Region">
            <option value="Territory">
        </datalist>
    </div>

    <x-admin.form.input
        name="code"
        label="Code"
        type="text"
        placeholder="Optional code"
        :value="$state->code ?? ''"
        maxlength="20"
    />

    <x-admin.form.input
        name="sort_order"
        label="Sort Order"
        type="number"
        :value="$state->sort_order ?? 0"
        min="0"
    />

    <div class="flex items-end">
        <x-admin.form.checkbox
            name="is_active"
            label="Active"
            :checked="$state->is_active ?? true"
            help="Inactive states will not appear in active selections."
        />
    </div>

</div>