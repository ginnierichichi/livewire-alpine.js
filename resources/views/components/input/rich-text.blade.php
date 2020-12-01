@props(['initialValue' => ''])

<div class="rounded-md shadow-sm"
     wire:ignore
     {{$attributes}}
     xdata
     @trix-blur="$dispatch('change', $event.target.value)">

    <input id="x" value="{{ $initialValue }}" type="hidden">
    <trix-editor input="x" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"></trix-editor>

{{--    <textarea {{$attributes}} rows="3" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"></textarea>--}}
</div>

