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
    <div class="mx-auto flex flex-col place-items-center">
        <div class="text-center mb-8">
            <h3 class="text-[24px] font-bold">
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
