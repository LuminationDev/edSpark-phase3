<script setup lang="ts">
import { computed, ref } from 'vue'
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
        required: false
    }
})
const emit = defineEmits(['update:modelValue','inputUpdate'])

const inputRef = ref(null)

const focus = () => {
    if (inputRef.value !== null) {
        inputRef.value.focus()
    }
}
const handleInput = (e) => {
    const target = e.target
    emit('update:modelValue', parseInt(target.value))
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
            ref="inputRef"
            :value="modelValue"
            :placeholder="placeholder"
            type="number"
            class="mr-1 border-2 rounded p-2 border-black text-black"
            :class="{
                '!border-red-500': v$ && v$.$error
            }"
            @input="handleInput"
        >
        <ErrorMessages :v$="v$" />
    </div>
</template>
