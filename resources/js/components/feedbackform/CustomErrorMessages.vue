<script setup>

import ErrorIconSmall from "@/js/components/svg/ErrorIconSmall.vue";

const props = defineProps({
    errorText: {
        type: String,
        required: true
    }
})

</script>

<template>
    <div class="flex-col">
        <div
            class="flex flex-col"
        >
            <div
                class="flex items-center flex-row text-red-500"
            >
                <ErrorIconSmall
                    class="fill-inactive h-5 max-w-none mr-2 shrink-0 stroke-inactive w-5"
                    alt="error icon"
                />
                <span class="text-sm"> {{ props.errorText }}</span>
            </div>
        </div>
    </div>
</template>
