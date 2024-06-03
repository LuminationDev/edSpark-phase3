<script setup lang="ts">
import {ref} from 'vue'

import ErrorMessages from './ErrorMessages.vue'

const props = defineProps({
    v$: {
        type: Object,
        required: true
    },
    modelValue: {
        type: Object,
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
    },
    withNoLeftMargin: {
        type: Boolean,
        required: false,
        default: false
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
    <div class="flex-col mb-4">
        <label
            class="h-8"
            :class="{'ml-2' : !props.withNoLeftMargin}"
            :for="fieldId"
        >
            <slot name="label" />
        </label>
        <input
            :id="fieldId"
            ref="inputRef"
            :value="modelValue"
            :placeholder="placeholder"
            type="text"
            class="border-[1px] border-gray-300 mr-1 p-2 rounded text-black placeholder:font-light"
            :class="{
                '!border-red-500': v$ && v$.$error
            }"
            @input="handleInput"
        >
        <ErrorMessages :v$="v$" />
    </div>
</template>
