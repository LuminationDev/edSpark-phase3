<script setup>
import {computed, onBeforeUnmount, onMounted, ref, watch} from 'vue'

import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";

const props = defineProps({
    rawContent: {
        type: String,
        required: true
    }
})

const sanitizedContent = computed(() => {
    if (props.rawContent.length > 0) {
        return edSparkContentSanitizer(props.rawContent)

    } else {
        return 'Sorry content not available'
    }
})

</script>

<template>
    <div
        class="max-w-full richTextContentContainer *:max-w-full"
        v-html="sanitizedContent"
    />
</template>
<style>
table{
    max-width: 100%;
}
</style>