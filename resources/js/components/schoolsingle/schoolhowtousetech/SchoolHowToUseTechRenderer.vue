<script setup>

import {computed} from "vue";

import SchoolHowToUseTechGallery from "@/js/components/schoolsingle/schoolhowtousetech/SchoolHowToUseTechGallery.vue";

const props = defineProps({
    techLandscape:{
        required: true,
        type: Array
    },
    techUsed:{
        required: true,
        type: Array
    }
})


const activeHowToUseTechItem = computed(() => {
    return props.techLandscape.filter(htuitem => props.techUsed.map(tuItem => tuItem.name).includes(htuitem.name))
})
const emits = defineEmits([])

const displayTitle = (code) => {
    switch (code) {
    case "IoT":
        return "Internet of Things"
    case "VR":
        return "Virtual Reality"
    case "AR":
        return "Augmented Reality"
    default:
        return code
    }
}

</script>

<template>
    <div class="container howToUseTechRenderer w-full">
        <div class="flex flex-col">
            <div
                v-for="(landscapeItem, index) in activeHowToUseTechItem"
                :key="index"
                class="EachTechItemRow flex flex-row mb-6 px-10 py-4 w-full   even:bg-slate-50 odd:bg-white"
            >
                <div class="flex flex-col textColumn w-full  lg:!w-1/2">
                    <div class="font-semibold text-2xl">
                        {{ displayTitle(landscapeItem.name) }}
                    </div>
                    <div
                        class="richTextContentContainer text"
                        v-html="landscapeItem.text"
                    />
                </div>
                <div class="flex flex-col imageGallery w-full  lg:!w-1/2">
                    <SchoolHowToUseTechGallery :image-array="landscapeItem.images" />
                    <!--                    <div class="imageDisplayLarge w-full">-->
                    <!--                        <img-->
                    <!--                            :src="landscapeItem.images[0]"-->
                    <!--                            alt="how to use image sample picture"-->
                    <!--                        >-->
                    <!--                    </div>-->
                </div>
            </div>
        </div>
    </div>
</template>
