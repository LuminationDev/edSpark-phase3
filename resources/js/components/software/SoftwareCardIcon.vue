<script setup>
import SoftwareIconGenerator from "@/js/components/software/SoftwareIconGenerator.vue";

const props = defineProps({
    softwareIconName: {
        type: String,
        required: true
    }
});

</script>

<template>
    <SoftwareIconGenerator
        class="h-16 w-16"
        :icon-name="props.softwareIconName"
    />
</template>
