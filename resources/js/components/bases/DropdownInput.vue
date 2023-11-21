<script setup lang="ts">
import {capitalize, computed} from 'vue'

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";



interface DropdownData {
    id: string | number
    name: string | number
}

const props = defineProps({
    v$: {
        type: Object,
        required: false
    },
    modelValue: {
        type: [String, Number],
        required: true
    },
    name: {
        type: String,
        required: true
    },
    data: {
        type: Array as () => Array<DropdownData>,
        required: true
    },
    fieldId: {
        type: String,
        required: true
    },
    dataType: {
        type: String,
        required: false,
        default: 'string'
    },
    placeholder: {
        type: String,
        required: false
    },
    labelText: {
        type: String,
        required: false
    },
    inputCallback: {
        type: Function,
        required: false,
        default: () => {
        }
    }
})


const showError = computed(() => {
    return props.v$ && props.v$.$error
})


const emit = defineEmits(['update:modelValue'])

const handleInputType = (dataType = 'string', value): void => {
    if (dataType === 'number') {
        emit('update:modelValue', Number(value))
    } else {
        emit('update:modelValue', value)
    }
}
</script>

<template>
    <div class="flex flex-col">
        <div class="flex flex-row labelRow">
            <slot name="label">
                <label
                    v-if="$slots.label"
                    class="flex flex-row mb-1 mt-2"
                    :class="{
                        '!h-0': !$slots.label
                    }"
                >{{ labelText }}</label>
            </slot>
        </div>
        <select
            :id="fieldId"
            :value="modelValue"
            :name="name"
            class="border-[1px] border-gray-300 p-2 rounded"
            :class="{
                'border-red-500': showError
            }"
            @input="
                handleInputType(props.dataType, $event.target.value)
            "
        >
            <option
                selected
                value=""
            >
                {{ placeholder ? placeholder : `Pick a ${name}` }}
            </option>
            <template
                v-for="item in data"
                :key="item.id"
            >
                <option :value="item.id">
                    {{ capitalize(item.name) }}
                </option>
            </template>
        </select>
        <ErrorMessages
            :v$="v$"
        />
    </div>
</template>
