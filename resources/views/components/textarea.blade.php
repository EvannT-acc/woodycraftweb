@props(['disabled' => false])
<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border rounded p-2']) !!}>{{ $slot }}</textarea>
