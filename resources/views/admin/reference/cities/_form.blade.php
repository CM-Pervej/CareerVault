<div class="grid grid-cols-1 gap-5 md:grid-cols-2">

    <div>
        <label class="mb-1 block text-sm font-semibold">Country <span class="text-error">*</span></label>
        <select name="country_id" id="country_id" class="select select-bordered w-full" required>
            <option value="">Select country</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}"
                    @selected(old('country_id', $city->country_id ?? '') == $country->id)>
                    {{ $country->name }}
                </option>
            @endforeach
        </select>
        @error('country_id')
            <p class="mt-1 text-xs text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">State / Division</label>
        <select name="state_id" id="state_id" class="select select-bordered w-full">
            <option value="">Select state / division</option>
            @foreach($states as $state)
                <option value="{{ $state->id }}"
                    data-country="{{ $state->country_id }}"
                    @selected(old('state_id', $city->state_id ?? '') == $state->id)>
                    {{ $state->name }} — {{ $state->country->name }}
                </option>
            @endforeach
        </select>
        @error('state_id')
            <p class="mt-1 text-xs text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">City Name <span class="text-error">*</span></label>
        <input type="text"
               name="name"
               value="{{ old('name', $city->name ?? '') }}"
               class="input input-bordered w-full"
               maxlength="100"
               required>
        @error('name')
            <p class="mt-1 text-xs text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">Code</label>
        <input type="text"
               name="code"
               value="{{ old('code', $city->code ?? '') }}"
               class="input input-bordered w-full"
               maxlength="20">
        @error('code')
            <p class="mt-1 text-xs text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-semibold">Sort Order</label>
        <input type="number"
               name="sort_order"
               value="{{ old('sort_order', $city->sort_order ?? 0) }}"
               class="input input-bordered w-full"
               min="0">
        @error('sort_order')
            <p class="mt-1 text-xs text-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-6 pt-7">
        <label class="label cursor-pointer justify-start gap-3">
            <input type="checkbox"
                   name="is_capital"
                   value="1"
                   class="checkbox checkbox-sm"
                   @checked(old('is_capital', $city->is_capital ?? false))>
            <span class="text-sm font-semibold">Capital city</span>
        </label>

        <label class="label cursor-pointer justify-start gap-3">
            <input type="checkbox"
                   name="is_active"
                   value="1"
                   class="checkbox checkbox-sm"
                   @checked(old('is_active', $city->is_active ?? true))>
            <span class="text-sm font-semibold">Active</span>
        </label>
    </div>

</div>

<div class="mt-6 flex items-center justify-end gap-2">
    <a href="{{ route('admin.cities.index') }}" class="btn btn-ghost">
        Cancel
    </a>

    <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-check mr-1"></i>
        {{ isset($city) ? 'Update City' : 'Create City' }}
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const country = document.getElementById('country_id');
    const state = document.getElementById('state_id');

    const filterStates = () => {
        const countryId = country.value;
        const selectedState = state.value;

        [...state.options].forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = option.dataset.country !== countryId;
        });

        const selected = state.querySelector(`option[value="${selectedState}"]`);

        if (selected && !selected.hidden) {
            state.value = selectedState;
        } else {
            state.value = '';
        }
    };

    country.addEventListener('change', filterStates);

    filterStates();
});
</script>