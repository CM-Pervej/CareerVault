@props([
    'name',
    'label',
    'checked' => false,
    'value' => '1',
    'disabled' => false,
    'help' => null,
])

@php
    $isChecked = old($name, $checked);
    $hasError = $errors->has($name);
@endphp

<div class="form-control">
    <input type="hidden" name="{{ $name }}" value="0">

    <label for="{{ $name }}" class="label cursor-pointer justify-start gap-3 p-0">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="checkbox"
            value="{{ $value }}"
            @checked($isChecked)
            @disabled($disabled)
            {{ $attributes->merge([
                'class' => 'checkbox checkbox-primary ' . ($hasError ? 'checkbox-error' : '')
            ]) }}
        >

        <span>
            <span class="font-semibold">{{ $label }}</span>

            @if($help)
                <span class="block text-xs opacity-70 mt-0.5"> {{ $help }} </span>
            @endif
        </span>
    </label>

    @error($name)
        <label class="label pt-1">
            <span class="label-text-alt text-error">{{ $message }}</span>
        </label>
    @enderror
</div>