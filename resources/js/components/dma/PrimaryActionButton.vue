<script setup lang="ts">

import {onBeforeUnmount, onMounted, ref} from "vue";

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
    autoSubmit: {
        type: Number,
        required: false,
        default: 0,
    }
});

const emit = defineEmits(['click'])

const timer = ref(null);
const progress = ref(null);

const handleEnter = (event => {
    if(!props.disabled && event.key === 'Enter') {
        handleClick();
    }
});

const handleClick = () => {
    clearTimeout(timer.value);
    timer.value = null;
    emit('click');
}

/*
 * NOTE: this component globally captures the enter keypress while mounted, regardless of element focus.
 * Only use one instance of this element at a time, and only when safe to capture key input.
 */

onMounted(() => {
    // globally capture enter keypress event
    window.addEventListener('keyup', handleEnter);
    if(props.autoSubmit) {
        // start timer
        timer.value = setTimeout(() => {
            emit('click');
        }, props.autoSubmit * 1000);

        // start progress animation
        setTimeout(() => {
            progress.value.style.width = '100%';
        },10);
    }
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
        <div
            v-if="props.autoSubmit"
            class="bg-white overflow-hidden relative rounded-full text-button"
            :class="{'opacity-50': disabled, 'hover:active:brightness-75': !disabled, 'hover:brightness-90':!disabled}"
        >
            <div
                ref="progress"
                class="absolute top-0 bottom-0 bg-black/20 w-0"
                :style="`transition: width ${props.autoSubmit}s linear;`"
            />
            <button
                class="p-2 px-7 relative text-black text-button z-10"
                :disabled="props.disabled"
                @click="handleClick"
            >
                <slot />
            </button>
        </div>
        <RoundButton
            v-else
            :disabled="props.disabled"
            @click="handleClick"
        >
            <slot />
        </RoundButton>
        <div class="hidden mt-4 opacity-80 text-small lg:block">
            Press Enter ⏎
        </div>
    </div>
</template>
<style></style>
