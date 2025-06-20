@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-500 text-sm font-medium leading-5 text-gray-900 dark:text-text-primary-dark focus:outline-none focus:border-indigo-700 dark:focus:border-indigo-600 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-text-secondary-dark hover:text-gray-700 dark:hover:text-text-primary-dark hover:border-gray-300 dark:hover:border-border-dark focus:outline-none focus:text-gray-700 dark:focus:text-text-primary-dark focus:border-gray-300 dark:focus:border-border-dark transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
