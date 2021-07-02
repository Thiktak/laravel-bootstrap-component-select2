@props([
    'label' => null,
    'placeholder' => null,
    'options' => [],
    'icon' => null,
    'prepend' => null,
    'append' => null,
    'size' => null,
    'help' => null,
])

@php
    $attributes = $attributes->class([
        'select2',
    ]);
@endphp

<div>
    <x-bs:select {{ $attributes }}></x-bs:select>
</div>
