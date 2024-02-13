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
        classStr = 'bg-black border-2 border-gray-800';
    } else {
        classStr = 'bg-gray-800';
    }
    if (props.disabled) {
        classStr += ' opacity-50'
    } else if (props.outline) {
        classStr += ' hover:border-gray-700';
    } else {
        classStr += ' hover:bg-gray-700';
    }
    return classStr;
})

</script>
<template>
    <button
        class="flex justify-between items-center flex-row font-bold mt-3 p-5 rounded-xl text-lg text-white w-full"
        :class="dynamicClasses"
        :disabled="props.disabled"
        @click="emit('click')"
    >
        <slot />
        <span
            v-if="props.hint"
            class="font-light hint text-gray-500 text-sm"
        >{{ props.hint }}</span>
    </button>
</template>
<style></style>
