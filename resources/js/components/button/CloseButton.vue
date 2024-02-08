<script setup lang="ts">

const emit = defineEmits(['click'])

</script>
<template>
    <button
        class="bg-gray-100 hover:bg-white cursor-pointer flex justify-center items-center h-8 rounded-full text-gray-800 w-8"
        @click="emit('click')"
    >
        X
    </button>
</template>
<style></style>
