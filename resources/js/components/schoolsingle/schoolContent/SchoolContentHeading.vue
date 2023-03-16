<script setup>
const props = defineProps({
    data: {
        type: Object,
        required: true
    }
})
</script>
<template>
    <div class="text-xl font-bold ">
        {{ data.data.text }}
    </div>
</template>