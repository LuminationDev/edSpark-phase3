<script setup>
import {computed} from "vue";

import {alignmentClassGenerator} from "@/js/helpers/stringHelpers";

const props = defineProps({
    data: {
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
        class="flex text-lg"
        :class="`${alignmentClass}`"
    >
        <span v-html="data.data.text" />
    </div>
</template>