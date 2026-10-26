@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'help' => null,
])

@php
    $selectedValue = old($name, $value);
    $hasError = $errors->has($name);
@endphp

<div class="form-control w-full">
    @if($label)
        <label for="{{ $name }}" class="label pb-1">
            <span class="label-text font-semibold">
                {{ $label }}
                @if($required)
                    <span class="text-error">*</span>
                @endif
            </span>
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge([
            'class' => 'select select-bordered w-full ' . ($hasError ? 'select-error' : '')
        ]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        {{ $slot }}
    </select>

    @if($help)
        <label class="label pt-1">
            <span class="label-text-alt opacity-70">{{ $help }}</span>
        </label>
    @endif

    @error($name)
        <label class="label pt-1">
            <span class="label-text-alt text-error">{{ $message }}</span>
        </label>
    @enderror
</div>