<x-dynamic-component
        :component="$getFieldWrapperView()"
        :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <!-- Interact with the `state` property in Alpine.js -->
        <img class="h-36 w-36"
             style="height:288px;width:288px;"
             alt="{{$getRecord()->alt}}"
             src="{{env('VITE_SERVER_IMAGE_API') . '/' . $getRecord()->uuid . "/original." . $getRecord()->file_extension}}"
        >
    </div>
</x-dynamic-component>
