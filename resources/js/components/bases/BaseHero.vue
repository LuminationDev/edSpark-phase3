<script setup>
import ArticleSingleSwoosh from '../svg/ArticleSingleSwoosh.vue';
import {computed} from 'vue'
import {imageURL} from "@/js/constants/serverUrl";
const props = defineProps({
    backgroundUrl:{
        type: String,
        required: false
    },
    color1:{
        type: String,
        required: false,
        default: "#002858"
    },
    color2:{
        type: String,
        required: false,
        default: "#0A7982"
    },
    color3:{
        type: String,
        required: false,
        default:"#0A7982"
    },
    swooshColorTheme:{
        type: String,
        required: false
    }
});

const heroBackground = computed(() => {
    if(!props.backgroundUrl){
        return `bg-gradient-to-r from-[${props.color1}] via-[${props.color2}] to-[${props.color3}]`
    } else{
        return `bg-[url(${imageURL}/${props.backgroundUrl.replace(' ',"%20")})] bg-blend-soft-light bg-center bg-no-repeat bg-gray-600`
    }
});

const setTheBackground = computed(() => {
    return props.backgroundUrl;
});

</script>

<template>
    <div class="BaseHeroContainer h-[720px] -mt-28 relative z-10">
        <div
            :class="`BaseHeroClipThisPath pb-[36px] pt-[190px] px-[48px] grid grid-cols-8 bg-cover bg-center bg-no-repeat h-full relative bg-[url(${imageURL}/${setTheBackground})]`"
        >
            <div class="BaseHeroBgOverlay absolute w-full h-full bg-gradient-to-r from-black via-black/75 via-40% z-10" />
            <div
                v-if="$slots.titleText || $slots.subtitleText1 || $slots.subtitleText2"
                class="col-span-5 p-2 relative z-20"
            >
                <slot name="smallTitle" />
                <h1
                    class="text-white text-[36px] font-semibold pb-8 uppercase"
                >
                    <slot name="titleText" />
                </h1>

                <p class="text-white flex flex-col gap-4 text-[18px] font-semibold" v-if="$slots.authorName">
                    <slot name="authorName" />
                </p>

                <p class="text-white flex flex-col gap-4 text-[16px] font-thin pb-4" v-if="$slots.contentDate">
                    <!-- <slot name="authorName" /> -->
                    <slot name="contentDate" />
                    <!-- <slot name="hardwareProvider" /> -->
                </p>

                <p class="text-white flex flex-col gap-4 text-[16px] font-thin pb-4" v-if="$slots.subtitleText1">
                    <!-- <slot name="authorName" /> -->
                    <slot name="subtitleText1" />
                    <!-- <slot name="hardwareProvider" /> -->
                </p>

                <p class="text-white flex flex-col gap-4 text-[14px] font-normal pb-4" v-if="$slots.hardwareProvider">
                    <slot name="hardwareProvider" />
                </p>

                <p
                    class="text-white text-[18px] font-normal"
                    :class="$slots.subtitleText2 ? 'mt-[36px]' : ''"
                    v-if="$slots.subtitleText2"
                >
                    <slot name="subtitleText2" />
                </p>
            </div>
            <div class="col-span-3 relative" v-if="$slots.icon">
                <div class="absolute right-12">
                    <slot name="icon" />
                </div>
            </div>
        </div>
        <div class="articleSwooshContainer relative w-full h-44 z-50">
            <ArticleSingleSwoosh
                :color-theme="swooshColorTheme"
            />
            <div class=" absolute mt-1 pt-2 pl-12 -top-9 w-full h-16 text-white font-base text-2xl">
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
