@php

        @endphp
<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex flex-row justify-around items-center gap-x-3">
            <h2 class="flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white"> edSpark Home</h2>
            <x-filament::button
                    color="gray"
                    icon="heroicon-m-arrow-left-on-rectangle"
                    labeled-from="sm"
                    tag="button"
                    type="submit"
                    onclick="window.location.href = window.origin + '/dashboard'"
            > Go to edSpark
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>