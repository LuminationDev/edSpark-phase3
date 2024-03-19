<script setup lang="ts">
import {onMounted, ref} from "vue";

import iconBack from '@/assets/images/dma/icons/arrow-left.svg';

const scrollArea = ref(null);

const showScrollNotice = ref(false);

onMounted(() => {
    if(scrollArea.value) {
        if(scrollArea.value.scrollHeight > scrollArea.value.clientHeight) {
            showScrollNotice.value = true;
        }
    }
});

const handleScroll = () => {
    if(showScrollNotice.value && scrollArea.value?.scrollTop > 10) {
        console.log("hide notice");
        showScrollNotice.value = false;
    }
}

</script>
<template>
    <div class="flex-1 overflow-hidden scroll-fade">
        <div
            ref="scrollArea"
            class="h-full pb-10 relative scroll-area z-50 md:overflow-x-none md:overflow-y-scroll"
            @scroll="handleScroll"
        >
            <slot />
        </div>
    </div>
    <div
        class="absolute align-middle duration-500 flex flex-row gap-2 opacity-0 pl-4 pr-3 py-2 rounded-lg scroll-notice transition-opacity"
        :class="{'opacity-100': showScrollNotice}"
    >
        Scroll for more
        <img
            :src="iconBack"
            class="inline rotate-90 scale-x-[-1]"
        >
    </div>
</template>

<style scoped lang="scss">

@media screen and (min-width: 768px) {
    .scroll-notice {
        background-color: #fff2;
        backdrop-filter: blur(40px);
        bottom: 0;
    }

    .scroll-fade {
        position: relative;
        mask-image: linear-gradient(transparent, black 5%, black 90%, transparent);
    }

    .scroll-area::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.4);
    }
}
</style>
