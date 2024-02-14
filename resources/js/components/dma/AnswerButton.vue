<script setup lang="ts">

import {computed} from "vue";

const props = defineProps({
    hint: {
        type: String,
        required: false,
    },
    outline: {
        type: Boolean,
        required: false,
    },
    disabled: {
        type: Boolean,
        required: false,
    }
})

const emit = defineEmits(['click'])

const dynamicClasses = computed(() => {
    let classStr = '';
    if (props.outline) {
        classStr = 'bg-black-1 border-2 border-gray-800';
    } else {
        classStr = 'bg-black-2';
    }
    if (props.disabled) {
        classStr += ' opacity-50'
    } else if (props.outline) {
        classStr += ' hover:border-gray-700';
    } else {
        classStr += ' hover:bg-black-3 active:bg-black-4';
    }
    return classStr;
})

</script>
<template>
    <button
        class="flex justify-between items-center flex-row mt-8 px-11 py-9 text-h4-caps text-white w-full"
        :class="dynamicClasses"
        :disabled="props.disabled"
        @click="emit('click')"
    >
        <slot />
        <span
            v-if="props.hint"
            class="font-light hint text-gray-500 text-small"
        >{{ props.hint }}</span>
    </button>
</template>

<style>
    button {
        border-radius: 16px
    }
</style>
