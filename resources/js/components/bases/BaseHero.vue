<script setup>
import ArticleSingleSwoosh from '../svg/ArticleSingleSwoosh.vue';
import {computed, onMounted, resolveDynamicComponent, ref} from 'vue'
import {imageURL} from "@/js/constants/serverUrl";
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";

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
        required: true
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
    console.log(props.swooshColorTheme);
    var useCustomColor = false;

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
    <div class="-mt-[9rem] lg:!h-[750px] BaseHeroContainer h-full relative z-10">
        <div
            class="
                2xl:!pt-44
                bg-cover
                grid
                grid-cols-8
                h-full
                pb-20
                pt-40
                px-3
                relative
                lg:!pb-0
                lg:!pt-40
                lg:!px-12
                xl:!pt-40
                "
            :style="'background-image: url(' + heroBackgroundLinkOnly +')'"
        >
            <div
                class="BaseHeroBgOverlay absolute bg-gradient-to-r from-black via-40% via-black/75 h-full w-full z-10"
            />
            <div
                v-if="$slots.titleText || $slots.subtitleText1 || $slots.subtitleText2"
                class="col-span-8 p-2 relative z-20 lg:!col-span-5"                
                :color-theme="swooshColorTheme"
            >
                <slot name="breadcrumb" />
                <h1
                    class="font-semibold pb-8 text-2xl text-white md:!text-3xl lg:!text-4xl xl:!text-5xl"
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
                    class="flex flex-col font-thin gap-4 my-4 text-base text-white"
                >
                    <slot name="contentDate" />
                </p>

                <p
                    v-if="$slots.subtitleText1"
                    class="flex flex-col font-thin gap-4 my-4 pb-4 text-base text-white"
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
                    class="font-normal h-auto mt-6 text-base text-white"
                >
                    <p class="line-clamp-3">
                        <slot name="subtitleText2" />
                    </p>
                    <slot name="subtitleContent" />
                </div>
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
        <div class="articleSwooshContainer relative w-full z-50 ">
            <ArticleSingleSwoosh
                :color-theme="swooshColorTheme"
                class="absolute -top-9 h-16 mt-1 w-full"
            />
            <div class="absolute -top-9 h-16 mt-1 w-full z-50  flex items-endfont-base pl-4 pt-2 text-base text-white md:!pl-12 md:!text-2xl">
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
