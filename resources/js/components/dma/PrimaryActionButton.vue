<script setup lang="ts">

import {onBeforeUnmount, onMounted} from "vue";

import RoundButton from "@/js/components/dma/RoundButton.vue";

const props = defineProps({
    disabled: {
        type: Boolean,
        required: false,
    },
    class: {
        type: String,
        required: false,
        default: '',
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
    window.addEventListener('keyup', handleEnter);
})
onBeforeUnmount(() => {
    // release enter keypress event
    window.removeEventListener('keyup', handleEnter);
})

</script>
<template>
    <div
        class="text-center"
        :class="props.class"
    >
        <RoundButton
            :disabled="props.disabled"
            @click="emit('click')"
        >
            <slot />
        </RoundButton>
        <div class="hidden mt-4 opacity-80 text-small lg:block">
            Press Enter ⏎
        </div>
    </div>
</template>
<style></style>
