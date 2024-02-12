<script setup lang="ts">

const emit = defineEmits(['click'])

</script>
<template>
    <button
        class="text-bold hover:text-gray-200 uppercase"
        @click="emit('click')"
    >
        <slot />
    </button>
</template>
<style></style>
