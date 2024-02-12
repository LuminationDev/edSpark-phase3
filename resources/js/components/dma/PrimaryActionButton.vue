<script setup lang="ts">

import {onBeforeUnmount, onMounted} from "vue";

import RoundButton from "@/js/components/dma/RoundButton.vue";

const props = defineProps({
    disabled: {
        type: Boolean,
        required: false,
    },
});

const emit = defineEmits(['click'])

const handleEnter = (event => {
    if(event.key === 'Enter') emit('click');
});

/*
 * NOTE: this component globally captures the enter keypress while mounted, regardless of element focus.
 * Only use one instance of this element at a time, and only when safe to capture key input.
 */

onMounted(() => {
    // globally capture enter keypress event
    window.addEventListener('keydown', handleEnter);
})
onBeforeUnmount(() => {
    // release enter keypress event
    window.removeEventListener('keydown', handleEnter);
})

</script>
<template>
    <div class="text-center">
        <RoundButton
            :disabled="props.disabled"
            @click="emit('click')"
        >
            <slot />
        </RoundButton>
        <div class="font-light mt-3 text-md">
            Press Enter ⏎
        </div>
    </div>
</template>
<style></style>
