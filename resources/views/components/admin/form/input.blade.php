@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'help' => null,
])

@php
    $inputValue = old($name, $value);
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

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $inputValue }}"
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        {{ $attributes->merge([
            'class' => 'input input-bordered w-full ' . ($hasError ? 'input-error' : '')
        ]) }}
    >

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