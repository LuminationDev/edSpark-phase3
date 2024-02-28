<script setup lang="ts">

import {computed} from "vue";

import EdSparkSlimSwoosh from "@/js/components/svg/EdSparkSlimSwoosh.vue";

type ValidLandingHeroBackgroundColor = 'navy' | 'teal' | 'darkTeal' | 'red'


const props = defineProps({
    title: {
        type: String,
        required: true
    },
    titleParagraph: {
        type: String,
        required: true
    },
    backgroundColor: {
        type: String as ValidLandingHeroBackgroundColor,
        required: false,
        default: 'darkTeal'
    },
    swooshColor:{
        type: String,
        required: false,
        default: 'darkTeal'
    }
})

const backgroundColorClass = computed(() => {
    switch (props.backgroundColor) {
    case 'teal':
        return 'bg-main-teal'
    case 'darkTeal':
        return 'bg-main-darkTeal'
    case 'navy':
        return 'bg-main-navy'
    case 'purple':
    case 'technologyPurple':
        return 'bg-secondary-grape'
    case 'blue':
    case 'partnerBlue':
        return 'bg-secondary-blueberry'
    default:
        return 'bg-main-darkTeal'
    }
})

</script>

<template>
    <div
        class="h-mainHero mb-4 relative"
        :class="backgroundColorClass"
    >
        <div class="grid grid-cols-6 h-full px-12 md:px-16">
            <div class="col-span-6 flex justify-center portrait:pt-10 flex-col gap-2 h-full md:!col-span-3">
                <h1
                    class="font-semibold pb-8 text-3xl text-white md:!text-[2.5rem] lg:!text-5xl lg:!text-[48px]"
                >
                    {{ props.title }}
                </h1>

                <p class="font-thin grid place-items-start text-white text-xl">
                    {{ props.titleParagraph }}
                </p>
            </div>
            <div
                class="hidden relative 
                welcomeRobot z-10 md:!col-span-2 md:block md:scale-75 lg:!ml-0 lg:!scale-100"
            >
                <slot name="robotIllustration" />
            </div>
        </div>
        <div
            class="2xl:-bottom-5 BaseLandingHeroSwoosh absolute -bottom-10 h-[120px] hidden w-full z-20 md:block xl:-bottom-8"
        >
            <EdSparkSlimSwoosh :color-theme="swooshColor" />
        </div>
    </div>
</template>
