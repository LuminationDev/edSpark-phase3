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
});

const alignmentClass = computed(() =>{
    return alignmentClassGenerator(props.alignment)
})


</script>
<template>
    <div
        class="flex flex-col"
        :class="`${alignmentClass}`"
    >
        <img
            class="max-h-[450px]"
            :src="data.data.file.url"
            :alt="data.data.caption"
        >
        <p :class="`${alignmentClass}`">
            {{ data.data.caption }}
        </p>
    </div>
</template>
