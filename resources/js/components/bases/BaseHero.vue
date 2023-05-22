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
})

const heroBackground = computed(() => {
    if(!props.backgroundUrl){
        return `bg-gradient-to-r from-[${props.color1}] via-[${props.color2}] to-[${props.color3}]`
    } else{
        return `bg-[url(${imageURL}/${props.backgroundUrl.replace(' ',"%20")})] bg-blend-soft-light bg-center bg-no-repeat bg-gray-600`
    }
})
</script>

<template>
    <div class="BaseHeroContainer h-[720px] -mt-28 relative -z-10">
        <div
            :class="'BaseHeroClipThisPath pb-[36px] pt-[190px] px-[48px] grid grid-cols-8 bg-cover h-full relative '+ heroBackground"
        >
            <div class="BaseHeroBgOverlay absolute w-full h-full bg-gradient-to-r from-black/75 z-10" />
            <div
                v-if="$slots.titleText || $slots.subtitleText1 || $slots.subtitleText2"
                class="col-span-5 p-2 relative z-20"
            >
                <h1
                    class="text-white text-[36px] font-semibold pb-8"
                >
                    <slot name="titleText" />
                </h1>

                <p class="text-white flex flex-row gap-4 text-[18px] font-normal pb-4">
                    <slot name="authorName" />
                    <slot name="subtitleText1" />
                    <slot name="hardwareProvider"/>
                </p>

                <p class="text-white text-[18px] font-normal">
                    <slot name="subtitleText2" />
                </p>
            </div>
            <div class="col-span-3 relative">
                <div class="absolute right-12">
                    <slot name="icon" />
                </div>
            </div>
        </div>
        <ArticleSingleSwoosh
            :color1="props.color1"
            :color2="props.color2"
            :color3="props.color3"
            class="absolute w-full -bottom-2"
        />
    </div>
</template>

<style>
    .BaseHeroClipThisPath {
        clip-path: polygon(100% 0, 100% 94%, 91% 98%, 71% 100%, 50% 100%, 0 100%, 0 0);
    }
</style>
