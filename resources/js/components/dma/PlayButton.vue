<script setup lang="ts">
import iconPlay from "@/assets/images/dma/icons/play.svg";

</script>
<template>
    <button
        class="
            bg-black/50
            active:bg-black/80
            hover:bg-black/60
            cursor-pointer
            flex
            justify-center
            items-center
            h-[112px]
            rounded-full
            w-[112px]
            "
    >
        <img :src="iconPlay">
    </button>
</template>

<style>
</style>
