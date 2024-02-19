<script setup lang="ts">
import iconX from '@/assets/images/dma/icons/x.svg';

</script>
<template>
    <button class="flex items-center flex-row gap-2">
        <div
            class="
                bg-white
                hover:active:bg-gray-200
                hover:bg-gray-100
                cursor-pointer
                flex
                justify-center
                items-center
                flex-row
                h-[24px]
                rounded-full
                w-[24px]
                md:!h-[52px]
                md:!w-[52px]
                "
        >
            <img
                :src="iconX"
                class="h-auto w-[18px] md:!w-auto"
            >
        </div><span class="uppercase md:hidden">Exit</span>
    </button>
</template>

<style>
</style>
