<script setup>
const props = defineProps({
    data:{
        type: Object,
        required: true
    }
});

console.log(props.data);
</script>
<template>
    <img
        :src="data.data.file.url"
    >
    <p class="text-center">
        {{ data.data.caption }}
    </p>
</template>
