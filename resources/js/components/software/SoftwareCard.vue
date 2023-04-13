<script setup>
import GenericCard from "@/js/components/card/GenericCard.vue";
const props = defineProps({
    software: {
        type: Object,
        required: true
    }
})
</script>
<template>
    <GenericCard
        :title="software.['post_title']"
        :display-content="software['post_content']"
        :display-author="software['post_author']"
    >
        <template #icon>
            <div>hehe</div>
        </template>
    </GenericCard>
</template>