<script setup>
import {computed, onMounted, ref,resolveDynamicComponent} from 'vue'

import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";
import {imageURL} from "@/js/constants/serverUrl";

import ArticleSingleSwoosh from '../svg/ArticleSingleSwoosh.vue';

const props = defineProps({
    backgroundUrl: {
        type: String,
        required: false
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



const heroBackground = computed(() => {
    if (!props.backgroundUrl) {
        return `bg-gradient-to-r from-[${props.color1}] via-[${props.color2}] to-[${props.color3}]`
    } else {
        return `bg-[url(${imageURL}/${props.backgroundUrl.replace(' ', "%20").replace(/\\/g, "")}')] bg-blend-soft-light bg-center bg-no-repeat bg-gray-600`
    }
})


const heroBackgroundLinkOnly = computed(() => {
    if (props.backgroundUrl) {
        return `${imageURL}/${props.backgroundUrl.replace(' ', "%20").replace(/\\/g, "")}`
    } else {
        return ''
    }
});



const gradientBg = ref('')

// :style="'background-image: ' + gradientBg +';'
onMounted(() => {
    let useCustomColor = false;

    if(schoolColorKeys.includes(props.swooshColorTheme)){
        console.log(props.swooshColorTheme);
        useCustomColor = true
    } 

    gradientBg.value = "background-image: linear-gradient(to left, "
        +(useCustomColor ? schoolColorTheme[props.swooshColorTheme]['light'] : schoolColorTheme['teal']['light'])+","
        +(useCustomColor ? schoolColorTheme[props.swooshColorTheme]['med'] : schoolColorTheme['teal']['med'])+","
        +(useCustomColor ? schoolColorTheme[props.swooshColorTheme]['dark'] : schoolColorTheme['teal']['dark'])
        +");"; 
})

const customFill = computed(() => {
    return gradientBg.value;
})


</script>


<template>
    <div class="mb-0 BaseHeroContainer relative z-10">
        <div
            class="
                bg-cover
                grid
                grid-cols-8
                h-full
                pb-4
                pt-20
                px-8
                relative
                "
            :style="'background-image: url(' + heroBackgroundLinkOnly +')'"
        >
            <div
                class="BaseHeroBgOverlay absolute bg-gradient-to-r from-black via-40% via-black/75 h-full w-full z-10"
            />
            <div
                v-if="$slots.titleText || $slots.subtitleText1 || $slots.subtitleText2"
                class="col-span-8 p-2 relative z-20"
                :color-theme="swooshColorTheme"
            >
                <slot name="breadcrumb" />
                <h1
                    class="font-semibold pb-4 text-2xl text-white md:!text-3xl lg:!text-4xl xl:!text-5xl"
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
                        class="flex flex-col font-semibold gap-4 text-[18px] text-white"
                    >
                        <slot name="authorName" />
                    </p>

                    <p
                        v-if="$slots.contentDate"
                        class="flex flex-col font-thin gap-4 mb-0 lg:mb-8 text-base text-white"
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
                        class="font-normal h-auto mt-6 pb-4 text-lg text-white lg:max-w-[70%]"
                    >
                        <p class="">
                            <slot name="subtitleText2" />
                        </p>
                        <slot name="subtitleContent" />
                    </div>
                </div>
            </div>
            <div
                class="bg-center bg-cover bg-no-repeat col-span-4 imageCover"
                :style="'background-image: url(' + heroBackgroundLinkOnly +')'"
            />

            <div
                v-if="$slots.icon"
                class="col-span-3 relative"
            >
                <div class="absolute right-12">
                    <slot name="icon" />
                </div>
            </div>
        </div>
        <div class="articleSwooshContainer mb-8 relative w-full z-10">
            <ArticleSingleSwoosh
                :color-theme="swooshColorTheme"
                class=""
            />
            <div class="-top-1 md:!pl-12 md:!text-2xl flex h-16 pl-4 pt-2 text-white text-xl w-full">
                <slot
                    name="submenu"
                />
            </div>
        </div>
    </div>
</template>

<style>
.BaseHeroClipThisPath {
    clip-path: polygon(100% 0, 100% 94%, 91% 98%, 71% 100%, 50% 100%, 0 100%, 0 0);
}
</style>
