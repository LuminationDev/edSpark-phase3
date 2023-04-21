<script setup>
import AdviceIconGenerator from "@/js/components/advice/AdviceIconGenerator.vue";
const props = defineProps({
    adviceIconName:{
        type: String, required: true
    }
})
</script>

<template>
    <AdviceIconGenerator :icon-name="props.adviceIconName" />
</template>