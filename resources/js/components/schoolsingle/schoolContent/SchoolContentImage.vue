<script setup>
const props = defineProps({
    data:{
        type: Object,
        required: true
    }
});
</script>
<template>
    <img
        :src="data.data.url"
    >
    <p class="text-center">
        {{ data.data.caption }}
    </p>
</template>
