@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500 focus:outline-none rounded-md shadow-sm transition duration-200']) !!}>