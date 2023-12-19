<script setup>
import {CirclesToRhombusesSpinner} from 'epic-spinners';

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
    },
    loaderMessageClass: {
        type: String,
        required: false,
        default: 'text-2xl font-bold'
    },
    loaderType: {
        type: String,
        required: false,
        default: 'page'

    }
});
</script>

<template>
    <div
        class="flex flex-col mx-auto place-items-center"
        :class="{
            'min-h-[80vh]' : props.loaderType === 'page'
        }"
    >
        <div
            class="mb-4 text-center"
            :class="{
                '!mb-8' : props.loaderType === 'page'
            }"
        >
            <h3
                :class="props.loaderMessageSize"
            >
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
