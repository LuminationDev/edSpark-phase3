<script setup lang="ts">
import {computed, ref} from 'vue'

import ErrorMessages from '@/js/components/bases/ErrorMessages.vue'

const props = defineProps({
    v$: {
        type: Object,
        required: true
    },
    modelValue: {
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
    },
    customStyleClass: {
        type: String,
        required: false,
        default: ''
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

const labelClass = computed(() => {
    let customClass = ''
    if (!props.withNoLeftMargin) {
        customClass = customClass + ' ml-2 '
    }
    if (props.customStyleClass) {
        customClass += props.customStyleClass
    }
    return customClass
})

defineExpose({
    focus
})

</script>

<template>
    <div class="flex-col mb-4">
        <label
            class="h-8"
            :class="labelClass"
        >
            <slot name="label" />
        </label>

        <input
            ref="inputRef"
            :value="modelValue"
            :placeholder="placeholder"
            type="text"
            class="border-[1px] border-gray-300 mr-1 p-2 rounded text-black"
            :class="{
                '!border-red-500': v$ && v$.$error
            }"
            @input="handleInput"
        >
        <ErrorMessages :v$="v$" />
    </div>
</template>
