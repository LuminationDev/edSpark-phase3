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
        class="max-h-[450px]"
        :src="data.data.file.url"
    >
    <p class="text-center">
        {{ data.data.caption }}
    </p>
</template>
