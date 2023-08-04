<script setup>
import ArticleSingleSwoosh from '../svg/ArticleSingleSwoosh.vue';
import {computed} from 'vue'
import {imageURL} from "@/js/constants/serverUrl";

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

const setTheBackground = computed(() => {
    // console.log(`${imageURL}/${props.backgroundUrl}`);
    return `${imageURL}/${props.backgroundUrl}`;
});


</script>

<template>
    <div class="-mt-28 lg:!h-[720px] BaseHeroContainer h-full relative z-10">
        <div
            :class="`BaseHeroClipThisPath pb-[36px] pt-[190px] px-3 lg:!px-12 grid grid-cols-8 bg-cover h-full relative bg-[url(${imageURL}/${setTheBackground}) `+ heroBackground"
            :style="'background-image: url(' + heroBackgroundLinkOnly +')'"
        >
            <div
                class="BaseHeroBgOverlay absolute bg-gradient-to-r from-black via-40% via-black/75 h-full w-full z-10"
            />
            <div
                v-if="$slots.titleText || $slots.subtitleText1 || $slots.subtitleText2"
                class="col-span-8 p-2 relative z-20 lg:!col-span-5"
            >
                <slot name="smallTitle" />
                <h1
                    class="font-semibold pb-8 text-2xl text-white uppercase md:!text-3xl lg:!text-4xl xl:!text-5xl"
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
                    class="flex flex-col font-thin gap-4 pb-4 text-[16px] text-white"
                >
                    <!-- <slot name="authorName" /> -->
                    <slot name="contentDate" />
                    <!-- <slot name="hardwareProvider" /> -->
                </p>

                <p
                    v-if="$slots.subtitleText1"
                    class="flex flex-col font-thin gap-4 pb-4 text-[16px] text-white"
                >
                    <!-- <slot name="authorName" /> -->
                    <slot name="subtitleText1" />
                    <!-- <slot name="hardwareProvider" /> -->
                </p>

                <p
                    v-if="$slots.hardwareProvider"
                    class="flex flex-col font-normal gap-4 pb-4 text-[14px] text-white"
                >
                    <slot name="hardwareProvider" />
                </p>

                <p
                    v-if="$slots.subtitleText2"
                    class="font-normal text-[18px] text-white"
                    :class="$slots.subtitleText2 ? 'mt-[36px]' : ''"
                >
                    <slot name="subtitleText2" />
                </p>
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
        <div class="articleSwooshContainer relative w-full z-50">
            <ArticleSingleSwoosh
                :color-theme="swooshColorTheme"
                class="scale-y-[300%] md:!scale-y-[200%] lg:!scale-y-[150%] xl:!scale-y-100"
            />
            <div class="absolute -top-9 font-base h-16 mt-1 pl-4 pt-2 text-base text-white w-full z-50 md:!pl-12 md:!text-2xl">
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
