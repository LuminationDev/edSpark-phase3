<script setup lang="ts">
import iconX from '@/assets/images/dma/icons/x.svg';

const emit = defineEmits(['click'])

</script>
<template>
    <button
        class="
            bg-white
            hover:active:bg-gray-200
            hover:bg-gray-100
            cursor-pointer
            flex
            justify-center
            items-center
            h-[52px]
            rounded-full
            w-[52px]
            "
        @click="emit('click')"
    >
        <img :src="iconX">
    </button>
</template>

<style>
</style>
