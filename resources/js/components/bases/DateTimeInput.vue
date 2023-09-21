<script setup lang="ts">
import {  ref } from 'vue'
import ErrorMessages from './ErrorMessages.vue'

const props = defineProps({
    v$: {
        type: Object,
        required: true
    },
    modelValue: {
        type: [Object,null],
        required: true
    },
    fieldId: {
        type: String,
        required: true
    },
    placeholder: {
        type: String,
        required: false,
        default: 'Select date and time'
    }
})
const emit = defineEmits(['update:modelValue','inputUpdate'])

const dateTimeInputRef = ref(null)

const focus = () => {
    if (dateTimeInputRef.value !== null) {
        dateTimeInputRef.value.focus()
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
            class="h-8 ml-2"
            :for="fieldId"
        >
            <slot name="label" />
        </label>
        <input
            :id="fieldId"
            ref="dateTimeInputRef"
            :value="modelValue"
            :placeholder="placeholder"
            type="datetime-local"
            class="border-[1px] border-gray-300 mr-1 p-2 rounded text-black"
            :class="{
                '!border-red-500': v$ && v$.$error
            }"
            @input="handleInput"
        >
        <ErrorMessages :v$="v$" />
    </div>
</template>
