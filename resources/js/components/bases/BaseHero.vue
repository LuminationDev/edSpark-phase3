<script setup>
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
        return `bg-[url(${imageURL}${props.backgroundUrl.replace(' ',"%20")})] bg-blend-soft-light bg-center bg-no-repeat bg-gray-600`
    }
})
</script>

<template>
    <div class="BaseHeroContainer h-[40vh]">
        <div
            :class="'py-[36px] px-[48px] grid grid-cols-8 bg-cover h-full '+ heroBackground"
        >
            <div
                v-if="$slots.titleText || $slots.subtitleText1 || $slots.subtitleText2"
                class="col-span-5 p-2"
            >
                <h1
                    class="text-white text-[36px] font-semibold pb-8"
                >
                    <slot name="titleText" />
                </h1>

                <p class="text-white text-[18px] font-normal pb-4">
                    <slot name="subtitleText1" />
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
    </div>
</template>
