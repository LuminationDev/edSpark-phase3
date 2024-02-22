<script setup lang="ts">
import iconBack from '@/assets/images/dma/icons/arrow-left.svg';

</script>
<template>
    <button
        class="
            bg-black-2
            active:bg-black-4
            hover:active:bg-gray-200
            hover:bg-black-3
            bg-white
            cursor-pointer
            flex
            justify-center
            items-center
            h-[52px]
            rounded-full
            w-[52px]
            "
    >
        <img :src="iconBack">
    </button>
</template>

<style>
</style>
