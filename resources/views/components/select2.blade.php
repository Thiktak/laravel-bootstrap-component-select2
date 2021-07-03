@php
    $model = $attributes->whereStartsWith('wire:model')->first();
    $key = $attributes->get('name', $model);
    $id = $attributes->get('id', $model);
@endphp

{{-- @ props([
    // Inherited
    'label' => null,
    'placeholder' => null,
    'options' => [],
    'icon' => null,
    'prepend' => null,
    'append' => null,
    'size' => null,
    'help' => null,
    'wire:model' => null,

    // and for Select2:
    'what' => null,
]) --}}


@php
    $attributes = $attributes->class([
        'select2',
    ]);

    if( empty($attributes->get('options')) ) {
        $attributes['options'] = [$this->$key => ''];
    }

    $uniqid = md5(uniqid());
@endphp

<div wire:ignore class="w-full">

    <!-- :label="$label" :placeholder="$placeholder" :icon="$icon" :prepend="$prepend" :append="$append" :size="$size" :help="$help" :options="$options" -->

    <x-bs::select data-sel-id="{{ $uniqid }}" 
        {{ $attributes }}></x-bs::select>
    <script>
        console.log('select2 <script>');

        var attach_{{ $uniqid }} = function() {
            var $element = $('[data-sel-id="{{ $uniqid }}"]');
            //console.log( $('[data-sel-id="{{ $uniqid }}"]').val() , @this.get('id2'));
            $element ///*.on('focus', function() {$(this)*/
               .select2({
                    theme: 'bootstrap-5',
                    //placeholder: '{{__('Select your option')}}',
                    dropdownParent: $('.modal-dialog').parent(),
                    triggerChange: true,
                    ajax: {
                        url: '{{ route('api.select2.search') }}?what={{ $what }}',
                    },
                    initSelection: function(element, callback) {
                        console.log('initSelection', element);
                        let id = $(element).val();
                        if(id !== '') {
                            
                        }
                    }
                    //allowClear: true
                });
            //});//*/

            var isInit = false;

            $(document).ready(function($) {
                console.log('ready');
                $.ajax('{{ route('api.select2.search') }}?what={{ $what }}&ids=true', {
                    data: {id: @this.get('{{ $key }}') },
                    dataType: 'json'
                }).then(function(data) {
                    isInit = true;
                    console.log('init ajax then', data);
                    for (let d = 0; d < data.results.length; d++) {
                        let item = data.results[d];

                        // Create the DOM option that is pre-selected by default
                        let option = new Option(item.text, item.id, true, true);

                        // Append it to the select
                        $element.append(option);
                    }

                    $element.trigger('change');
                    isInit = false;
                    //callback(data.results);
                });/*.done(function(data) {
                    console.log('init ajax done', data);
                    $('[data-sel-id="{{ $uniqid }}"]').trigger('change');
                });//*/
            });

            $('[data-sel-id="{{ $uniqid }}"]').on('change', function (e) {
                if( isInit ) {
                    return;
                }
                
                let elementName = $(this).attr('id');
                var data = $(this).select2('val');
                console.log('@ this.set("model:' + elementName + '", ', data, ')');
                @this.set('' + elementName, data);
            });
        };

        

        document.addEventListener('$refresh', function() {
            console.log('?refresh');
            attach_{{ $uniqid }}();
        });


        document.addEventListener('show.bs.modal', function() {
            console.log('?show.bs.modal');
            attach_{{ $uniqid }}();
        });

        //document.addEventListener('livewire:load', function () {
        $(document).ready(function() {
            console.log('livewire:load select2');
            attach_{{ $uniqid }}();
        });
    </script>
</div>
