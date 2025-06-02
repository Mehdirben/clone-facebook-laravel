<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-background-card-dark border border-gray-300 dark:border-border-dark rounded-md font-semibold text-xs text-gray-700 dark:text-text-primary-dark uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-background-hover-dark focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-background-dark disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
