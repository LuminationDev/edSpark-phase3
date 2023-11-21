<script setup lang="ts">
import {ref} from 'vue'

import ErrorMessages from './ErrorMessages.vue'

const props = defineProps({
    v$: {
        type: Object,
        required: true
    },
    modelValue: {
        type: String,
        required: true
    },
    fieldId: {
        type: String,
        required: true
    },
    placeholder: {
        type: String,
        required: false,
        default: 'Insert value here'
    }
})
const emit = defineEmits(['update:modelValue', 'inputUpdate'])

const inputRef = ref(null)

const focus = () => {
    if (inputRef.value !== null) {
        inputRef.value.focus()
    }
}
const handleInput = (e) => {
    const target = e.target
    emit('update:modelValue', target.value)
    emit('inputUpdate')
}

defineExpose({
    focus
})
</script>

<template>
    <div class="flex-col mb-4 relative">
        <label
            class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none
                    peer-focus:text-xs
                    peer-focus:-translate-y-1.5
                    peer-focus:text-gray-500
                    peer-[:not(:placeholder-shown)]:text-xs
                    peer-[:not(:placeholder-shown)]:-translate-y-1.5
                    peer-[:not(:placeholder-shown)]:text-gray-500"
            for="hs-floating-input-email"
        >
            <slot name="label"/>
        </label>
        <input
            id="hs-floating-input-email"
            ref="inputRef"
            :value="modelValue"
            :placeholder="placeholder"
            type="email"
            class="peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600
                    focus:pt-6
                    focus:pb-2
                    [&:not(:placeholder-shown)]:pt-6
                    [&:not(:placeholder-shown)]:pb-2
                    autofill:pt-6
                    autofill:pb-2"
            :class="{
                '!border-red-500': v$ && v$.$error
            }"
            @input="handleInput"
        >
        <ErrorMessages :v$="v$"/>
    </div>
</template>
