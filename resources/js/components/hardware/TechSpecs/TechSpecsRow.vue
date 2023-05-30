<script setup>
import {ref, computed} from 'vue'

const props = defineProps({
    label:{
        type: String,
        required: true
    },
    value:{
        type: String,
        required: true
    },
    isList:{
        type: Boolean,
        required: false,
        default: false
    }
})

const emits = defineEmits([])


</script>

<template>
    <div class="techSpecsRow flex flex-row border-b-2 py-4">
        <div class="techSpecsLabel w-1/2 font-semibold">
            {{ props.label }}
        </div>
        <div
            v-if="props.isList"
            class="techSpecsValue w-1/2 font-base"
        >
            <ul class="valueList pl-6">
                <li
                    v-for="(feature, index) in value.split(',')"
                    :key="index"
                    class="list-disc capitalize"
                >
                    {{ feature }}
                </li>
            </ul>
        </div>
        <div
            v-else
            class="techSpecsValue w-1/2 font-base"
        >
            {{ props.value }}
        </div>
    </div>
</template>
