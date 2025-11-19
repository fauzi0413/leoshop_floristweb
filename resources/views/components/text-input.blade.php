@props(['disabled' => false])

<input autocomplete="off" @disabled($disabled)
    {{ $attributes->merge([
        'class' => 'bg-white border-pink-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm'
    ]) }}>
