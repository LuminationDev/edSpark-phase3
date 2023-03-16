<script setup>
const props = defineProps({
    data: {
        type: Object,
        required: true
    }
})

</script>
<template>
    <div class="text-lg ">
        {{ data.data.text }}
    </div>
</template>