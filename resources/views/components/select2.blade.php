@props([
    // Inherited
    'label' => null,
    'placeholder' => null,
    'options' => [],
    'icon' => null,
    'prepend' => null,
    'append' => null,
    'size' => null,
    'help' => null,

    // and for Select2:
    'what' => null,
])

@php
    $attributes = $attributes->class([
        'select2',
    ]);

    $id2 = md5(uniqid());
@endphp

<div wire:ignore class="w-full">
    <x-bs::select :label="$label" :placeholder="$placeholder" :icon="$icon" :prepend="$prepend" :append="$append" :size="$size" :help="$help"
        data-sel-id="{{ $id2 }}" {{ $attributes }}></x-bs::select>
    <script>
        console.log('select2 <script>');

        var attach_{{ $id2 }} = function() {
            $('[data-sel-id="{{ $id2 }}"]')/*.on('focus', function() {
                $(this)*/.select2({
                    theme: 'bootstrap-5',
                    placeholder: '{{__('Select your option')}}',
                    dropdownParent: $('.modal-dialog').parent(),
                    ajax: {
                        url: '{{ route('api.select2.search') }}?what={{ $what }}',
                    }
                    //allowClear: true
                });
            //});
            $('[data-sel-id="{{ $id2 }}"]').on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2('val');
                console.log('@ this.set("model:' + elementName + '", ', data, ')');
                @this.set('' + elementName, data);
            });
        };

        

        document.addEventListener('$refresh', function() {
            console.log('?refresh');
            attach_{{ $id2 }}();
        });


        document.addEventListener('show.bs.modal', function() {
            console.log('?show.bs.modal');
            attach_{{ $id2 }}();
        });

        //document.addEventListener('livewire:load', function () {
        $(document).ready(function() {
            console.log('livewire:load select2');
            attach_{{ $id2 }}();
        });
    </script>
</div>
