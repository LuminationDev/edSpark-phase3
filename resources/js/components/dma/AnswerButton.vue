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
    },
    highlighted: {
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
    } else {
        if (props.highlighted) {
            if (props.outline) {
                classStr += ' border-gray-600';
            } else {
                classStr += ' bg-black-4';
            }
        } else {
            if (props.outline) {
                classStr += ' hover:border-gray-700 active:border-gray-600';
            } else {
                classStr += ' hover:bg-black-3 active:bg-black-4';
            }
        }
    }
    return classStr;
})

</script>

<template>
    <button
        class="
            duration-100
            flex
            justify-between
            items-center
            flex-row
            mt-4
            normal-case
            px-4
            py-6
            rounded-2xl
            text-h4-caps
            text-white
            tracking-normal
            transition-colors
            w-full
            md:!mt-6
            md:!px-11
            md:!py-9
            "
        :class="dynamicClasses"
        :disabled="props.disabled"
        @click="emit('click')"
    >
        <span class="flex-1 text-center lg:!text-left">
            <slot />
        </span>
        <span
            v-if="props.hint"
            class="hidden hint normal-case text-gray-500 text-small lg:!block"
        >{{ props.hint }}</span>
    </button>
</template>
