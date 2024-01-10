<script setup lang="ts">

import {computed} from "vue";

import DashboardSwoosh from "@/js/components/svg/DashboardSwoosh.vue";

type ValidLandingHeroBackgroundColor = 'navy' | 'teal' | 'darkTeal'


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
        default: 'teal'
    }
})

const backgroudColorClass = computed(() => {
    switch (props.backgroundColor) {
    case 'teal':
        return 'bg-main-teal'
    case 'darkTeal':
        return 'bg-main-darkTeal'
    case 'navy':
        return 'bg-main-navy'
    case 'purple':
        return 'bg-secondary-grapeDark'
    default:
        return 'teal'
    }
})

</script>

<template>
    <div
        class="h-mainHero mb-8 relative"
        :class="backgroudColorClass"
    >
        <div class="grid grid-cols-6 h-full px-16 md:px-16">
            <div class="col-span-6 flex justify-center flex-col gap-2 h-full md:!col-span-3">
                <h1
                    class="font-semibold pb-8 text-3xl text-white md:!text-[2.5rem] lg:!text-5xl lg:!text-[48px]"
                >
                    {{ props.title }}
                </h1>

                <p class="font-normal grid place-items-start text-white text-xl">
                    {{ props.titleParagraph }}
                </p>
            </div>
            <div
                class="hidden relative welcomeRobot z-10 md:!col-span-2 md:block md:scale-75 lg:!ml-0 lg:!scale-100"
            >
                <slot name="robotIllustration" />
            </div>
        </div>
        <div
            class="2xl:-bottom-4 BaseLandingHeroSwoosh absolute -bottom-14 h-[120px] hidden w-full z-20 md:block xl:-bottom-8"
        >
            <img
                src="@/assets/images/BaseLandingHeroSwoosh.png"
                alt="A curved line graphic"
                class="object-contain scale-x-[101.5%] w-full"
            >
        </div>
    </div>
</template>
