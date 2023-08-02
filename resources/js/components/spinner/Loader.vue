<script setup>
import { CirclesToRhombusesSpinner } from 'epic-spinners';

const props = defineProps({
    loaderColor: {
        type: String,
        required: false,
        default: '#339999'
    },
    loaderMessage: {
        type: String,
        required: false,
        default: 'Loading'
    }
});
</script>

<template>
    <div class="flex flex-col min-h-[80vh] mx-auto place-items-center">
        <div class="mb-8 text-center">
            <h3 class="font-bold text-[24px]">
                {{ loaderMessage }}
            </h3>
        </div>

        <CirclesToRhombusesSpinner
            :animation-duration="1200"
            :circles-num="3"
            :circle-size="15"
            :color="loaderColor"
        />
    </div>
</template>
