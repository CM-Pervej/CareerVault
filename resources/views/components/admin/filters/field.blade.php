@props([
    'field' => [],
])

@php
    $type = $field['type'] ?? 'select';

    $name = $field['name'] ?? '';
    $label = $field['label'] ?? '';
    $icon = $field['icon'] ?? '';
    $value = $field['value'] ?? null;

    $placeholder = $field['placeholder'] ?? null;

    $options = $field['options'] ?? [];

    $span = $field['span'] ?? '';
    $class = $field['class'] ?? '';

    $fieldClass = trim("form-control min-w-0 {$span} {$class}");

    $options = is_array($options)
        ? $options
        : collect($options)->toArray();
@endphp

@if($type === 'search')

    {{-- ========================= SEARCH ========================= --}}
    <div class="{{ $fieldClass }}">
        <div class="label py-0 pb-1.5">
            <span class="label-text text-xs font-bold">
                @if($icon)
                    <i class="{{ $icon }} mr-1"></i>
                @endif

                {{ $label }}
            </span>
        </div>

        <label class="input input-bordered flex w-full min-w-0 items-center gap-3">
            <i class="fa-solid fa-magnifying-glass shrink-0 text-base-content/40"></i>
            <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="min-w-0 grow"/>

            @if(filled($value))
                <a href="{{ request()->fullUrlWithQuery([$name => null]) }}" class="btn btn-ghost btn-xs btn-circle shrink-0" title="Clear {{ strtolower($label) }}">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </label>
    </div>

@elseif($type === 'select')

    {{-- ========================= SELECT ========================= --}}
    <label class="{{ $fieldClass }}">
        <div class="label py-0 pb-1.5">
            <span class="label-text text-xs font-bold">
                @if($icon)
                    <i class="{{ $icon }} mr-1"></i>
                @endif

                {{ $label }}
            </span>
        </div>

        <select name="{{ $name }}" class="select select-bordered w-full min-w-0" onchange="this.form.submit()">
            <option value="">
                {{ $field['empty_label'] ?? 'Select' }}
            </option>

            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    </label>

@endif