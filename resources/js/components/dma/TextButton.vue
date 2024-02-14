<script setup lang="ts">

const emit = defineEmits(['click'])

</script>
<template>
    <button
        class="text-h5-caps active:hover:text-gray-200 hover:text-gray-100"
        @click="emit('click')"
    >
        <slot />
    </button>
</template>
