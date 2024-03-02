<script setup>
import {computed, onMounted, onUnmounted, ref} from "vue";

const props = defineProps({
    backgroundColor: {
        type: String,
        required: false,
        default: 'teal'
    }
})


import {useWindowStore} from "@/js/stores/useWindowStore";
const windowStore = useWindowStore()

import resolveConfig from 'tailwindcss/resolveConfig'
import tailwindConfig from '../../../../tailwind.config.ts';


const shadyBackgroundColorClass = computed(() => {
    switch (props.backgroundColor) {
    case 'white':
        return 'bg-white'
    case 'teal':
        return 'bg-main-teal/10'
    case 'darkTeal':
        return 'bg-main-darkTeal/20'
    case 'navy':
        return 'bg-main-navy/20'
    case 'purple':
        return 'bg-secondary-grapeDark/20'
    case 'blue':
        return 'bg-secondary-blueberry/10'
    default:
        return 'teal'
    }
})

const isMed = ref('');

const windowWidthCheck = () => {
    // windowWidth.value = window.innerWidth;
    isMed.value = window.innerWidth < scrLg;
    windowStore.isMed = isMed;
    console.log("Width = "+ window.innerWidth +", "+isMed.value+", "+windowStore.isMed);
};

const handleResize = () => windowWidthCheck()

onMounted(async () => {
    window.addEventListener('resize', handleResize);
    handleResize();
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});

const { theme } = resolveConfig(tailwindConfig);

const scrMd = parseInt(theme.screens.md);
const scrLg = parseInt(theme.screens.lg);

// const isMed = computed(() => {
//     var isMed = windowWidth.value < scrLg;
//     return isMed;
// })


</script>

<template>
    <div
        class="flex justify-center flex-col px-8 py-16 xl:!px-16"
        :class="shadyBackgroundColorClass"
    >
        <div
            class="flex justify-between items-center sm:items-start flex-col justify-center sm:flex-row sectionHeader w-full"
            :class="{'mb-8' : $slots.content}"
        >
            <div class="flex flex-col titleAndSubtitle w-full">
                <div
                    class="font-medium text-3xl sm:text-4xl"
                    :class="{'mb-4' : $slots.subtitle}"
                >
                    <slot name="title" />
                </div>
                <div
                    v-if="$slots.subtitle"
                    class="font-base text-lg mb-8"
                >
                    <slot name="subtitle" />
                </div>
            </div>
            <div class="flex justify-start items-start w-full sm:w-64 [&>button]:w-full sm:[&>button]:w-64">
                <slot name="button" />
            </div>
        </div>
        <div class="UserActionBa w-full">
            <slot name="sectionAction" />
        </div>
        <div class="sectionContent">
            <slot name="content" />
        </div>
    </div>
</template>
