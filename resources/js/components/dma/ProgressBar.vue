<script setup lang="ts">

import {computed} from "vue";

const props = defineProps({
    percent: {
        type: Number,
        default: 0,
    },
})
const progressPercent = computed(() => {
    if (isNaN(props.percent)) return 0;
    return Math.min(Math.max(props.percent,0), 100);
})


</script>
<template>
    <div class="bg-white/10 flex-1 h-2 progress-bar-track rounded-full">
        <div
            class="bg-white duration-500 h-full progress-bar rounded-full transition-all"
            :style="`width: ${progressPercent}%`"
        />
    </div>
</template>
<style></style>
