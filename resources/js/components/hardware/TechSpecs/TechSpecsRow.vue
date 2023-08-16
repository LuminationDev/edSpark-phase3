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
    <div class="border-b-2 flex flex-row py-4 techSpecsRow">
        <div class="font-semibold techSpecsLabel w-1/2">
            {{ props.label }}
        </div>
        <div
            v-if="props.isList"
            class="font-sm techSpecsValue w-1/2 md:!font-base"
        >
            <ul class="pl-6 valueList">
                <li
                    v-for="(feature, index) in value.split(',')"
                    :key="index"
                    class="capitalize list-disc"
                >
                    {{ feature }}
                </li>
            </ul>
        </div>
        <!--   or   -->
        <div
            v-else
            class="font-sm techSpecsValue w-1/2 md:!font-base"
        >
            {{ props.value }}
        </div>
    </div>
</template>
