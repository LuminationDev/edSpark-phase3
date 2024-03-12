<script setup>
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from 'vue'

import EdSparkSlimSwoosh from "@/js/components/svg/EdSparkSlimSwoosh.vue";
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";
import {imageURL} from "@/js/constants/serverUrl";
import {useWindowStore} from "@/js/stores/useWindowStore";

import ArticleSingleSwoosh from '../svg/ArticleSingleSwoosh.vue';

const props = defineProps({
    backgroundUrl: {
        type: String,
        required: false,
        default: ''
    },
    backgroundServerUrl: {
        type: String,
        required: false,
        default: imageURL
    },
    color1: {
        type: String,
        required: false,
        default: "#002858"
    },
    color2: {
        type: String,
        required: false,
        default: "#0A7982"
    },
    color3: {
        type: String,
        required: false,
        default: "#0A7982"
    },
    swooshColorTheme: {
        type: String,
        required: false,
        default: 'teal'
    }
})


const heroBackgroundLinkOnly = computed(() => {
    if (props.backgroundUrl) {
        return `${props.backgroundServerUrl}/${props.backgroundUrl.replace(' ', "%20").replace(/\\/g, "")}`
    } else {
        return ''
    }
});


const gradientBg = ref('')

onMounted(() => {
    let useCustomColor = false;

    if (schoolColorKeys.includes(props.swooshColorTheme)) {
        console.log(props.swooshColorTheme);
        useCustomColor = true
    }

    console.log("Custom? "+useCustomColor+", "+props.swooshColorTheme);

    gradientBg.value = "background-image: linear-gradient(to left, "
        + (useCustomColor ? schoolColorTheme[props.swooshColorTheme]['med'] : schoolColorTheme['teal']['med']) + ","
        + (useCustomColor ? schoolColorTheme[props.swooshColorTheme]['med'] : schoolColorTheme['teal']['med']) + ","
        + (useCustomColor ? schoolColorTheme[props.swooshColorTheme]['dark'] : schoolColorTheme['teal']['dark'])
        + ");";

        console.log(gradientBg.value)
})

const windowStore = useWindowStore()
const {windowWidth} = storeToRefs(windowStore)

const heroBackgroundSwitch = computed(() => {
    if (windowWidth.value < 1024) {
        return "background-image: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('" + heroBackgroundLinkOnly.value + "') !important;  background-position: center center;  background-color:white; background-size:cover"
    } else {
        return ''
    }
})

const heroBackgroundColor = computed(() => {
    switch (props.swooshColorTheme) {
    
    // case 'banana':
    //     return 'bg-secondary-banana'

    // case 'cherry':
    //     return 'bg-secondary-cherry'

    //     case 'peach':
    //         return 'bg-secondary-peach'

    // case 'navy':
    //     return 'bg-main-navy'
        
    // case 'grape':
    // case 'purple':
    case 'technologyPurple':
        return 'bg-secondary-grapeWeb'

    // case 'blue':
    case 'partnerBlue':
    // case 'blueberry':
        return 'bg-secondary-blueberry'

    case 'teal':
    case 'darkTeal':
    default:
        return 'bg-main-darkTeal'
    }
})

// const heroBackgroundColor = computed(() => {
//     switch (props.swooshColorTheme) {
    
//     case 'navy':
//         return 'bg-main-navy'
//     case 'purple':
//     case 'technologyPurple':
//         return 'bg-secondary-grapeDark'
//     case 'blue':
//     case 'partnerBlue':
//         return 'bg-secondary-blueberry'
//     case 'teal':
//     case 'darkTeal':
//     default:
//         return 'bg-main-darkTeal'
//     }
// })
</script>



<!-- :class="heroBackgroundColor" -->
<!-- grid grid-cols-10 -->

<template>
    <div class="BaseHeroContainer h-mainHero max-h-mainHero mb-0 overflow-y-hidden relative z-10">
        <div
            class="flex flex-row h-full relative"
        >
            <div
                class="
                    HeroSolidColor
                    bg-center
                    bg-contain
                    bg-no-repeat
                    col-span-10
                    h-full
                    pt-14
                    px-11
                    lg:!col-span-6
                    "
                style="flex:1"
                :style="heroBackgroundSwitch"
                :class="heroBackgroundColor"
            >
                <div
                    v-if="$slots.titleText || $slots.subtitleText1 || $slots.subtitleText2"
                    class="col-span-8 h-full p-2 relative z-20 xl:mt-8"
                >
                    <slot name="breadcrumb" />
                    <h1
                        class="font-semibold pb-4 text-2xl text-white md:!text-2xl lg:!text-2xl xl:!text-3xl"
                    >
                        <slot name="titleText" />
                    </h1>
                    <div
                        v-if="$slots.additionalTags"
                        class="flex flex-row flex-wrap gap-4 max-w-full w-full"
                    >
                        <slot name="additionalTags" />
                    </div>

                    <p
                        v-if="$slots.authorName"
                        class="flex flex-col font-semibold gap-4 text-lg text-white"
                    >
                        <slot name="authorName" />
                    </p>

                    <p
                        v-if="$slots.contentDate"
                        class="flex flex-col font-light gap-4 mb-0 lg:mb-4 text-base text-white"
                    >
                        <slot name="contentDate" />
                    </p>

                    <p
                        v-if="$slots.subtitleText1"
                        class="flex flex-col font-thin gap-4 mb-4 pb-4 text-base text-white"
                    >
                        <slot name="subtitleText1" />
                    </p>

                    <p
                        v-if="$slots.hardwareProvider"
                        class="flex flex-col font-normal gap-4 pb-4 text-base text-white"
                    >
                        <slot name="hardwareProvider" />
                    </p>

                    <div
                        v-if="$slots.subtitleText2"
                        class="font-normal h-auto mt-4 pb-4 text-base text-white hidden"
                    >
                        <p class="">
                            <slot name="subtitleText2" />
                        </p>
                        <slot name="subtitleContent" />
                    </div>
                </div>
            </div>
            <div
                class="bg-center bg-cover bg-no-repeat bg-white hidden imageCover lg:!block lg:!col-span-4"
                :style="'background-image: url(' + heroBackgroundLinkOnly +')'"
                style="width: 500px; height: auto"
            />
        </div>

        <div
            v-if="$slots.icon"
            class="col-span-3 relative"
        >
            <div class="absolute right-12">
                <slot name="icon" />
            </div>
        </div>
    </div>
    <div class="articleSwooshContainer relative w-full z-10">
        <ArticleSingleSwoosh
            v-if="$slots.submenu"
            :color-theme="swooshColorTheme"
            class=""
        />
        <div
            v-else
            class="BaseHeroSwooshPositioningContainer absolute -top-20 h-[120px] hidden w-full z-20 md:block xl:!-top-24"
        >
            <EdSparkSlimSwoosh :color-theme="swooshColorTheme" />
        </div>
        <div
            v-if="$slots.submenu"
            class="-top-1 md:!pl-12 md:!text-2xl flex h-16 pl-4 pt-2 text-white text-xl w-full"
        >
            <slot
                name="submenu"
            />
        </div>
    </div>
</template>

<style>
.BaseHeroClipThisPath {
    clip-path: polygon(100% 0, 100% 94%, 91% 98%, 71% 100%, 50% 100%, 0 100%, 0 0);
}
</style>
