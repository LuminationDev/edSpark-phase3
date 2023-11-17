<script setup>
import {computed} from "vue";

import {alignmentClassGenerator} from "@/js/helpers/stringHelpers";

const props = defineProps({
    data:{
        type: Object,
        required: true
    },
    alignment:{
        type: String,
        required: false,
        default: 'left'
    }
})
const alignmentClass = computed(() =>{
    return alignmentClassGenerator(props.alignment)
})

</script>
<template>
    <div
        v-if="data.data.style === 'ordered'"
        :class="`flex ${alignmentClass}`"
    >
        <ol class="list-decimal ml-2">
            <li
                v-for="(listItem,index) in data.data.items"
                :key="index"
                class="ml-1 pb-1"
            >
                <span v-html="listItem" />
            </li>
        </ol>
    </div>
    <div
        v-else
        :class="`flex ${alignmentClass}`"
    >
        <ul>
            <li
                v-for="(listItem,index) in data.data.items"
                :key="index"
                class="list-disc pb-1"
            >
                <span v-html="listItem" />
            </li>
        </ul>
    </div>
</template>