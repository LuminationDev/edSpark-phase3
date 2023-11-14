<script setup>
import purify from "dompurify";
import { computed } from 'vue';

import EventDateListRenderer from "@/js/components/events/Renderer/EventDateListRenderer.vue";

const props = defineProps({
    content: {
        type: Object,
        required: true
    }
});


let contentType = 'dateItems';
contentType = Object.keys(props.content['data']['extra_content'])[0]


const itemArray = computed(() => {
    return props.content['data']['extra_content'][contentType]['item']
});
</script>

<template>
    <div class="extraContentRendererContainer flex flex-col my-2 px-4 py-2">
        <template v-if="contentType === 'date_items'">
            <EventDateListRenderer :item-array="itemArray" />
        </template>
        <template v-else>
            <div v-html="purify.sanitize(itemArray)" />
        </template>
    </div>
</template>
