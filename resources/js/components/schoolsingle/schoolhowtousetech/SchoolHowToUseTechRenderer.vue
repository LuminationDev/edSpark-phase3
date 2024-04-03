<script setup>

import {computed, ref} from "vue";

import SchoolHowToUseTechGallery from "@/js/components/schoolsingle/schoolhowtousetech/SchoolHowToUseTechGallery.vue";
import {stripHtmlTags} from "@/js/helpers/stringHelpers";

const props = defineProps({
    techLandscape: {
        required: true,
        type: Array
    },
    techUsed: {
        required: true,
        type: Array
    }
})
// simple function that check the summary to determine that it's populated
const howToUseTechHasContent = (howToUseText) => {
    const lines = stripHtmlTags(howToUseText).split('\n');
    const noEmptyContent = lines.filter(item => Boolean(item) && item !== '&nbsp;')
    const idxOfSummaryHeading = noEmptyContent.indexOf('Summary')
    const idxOfLearningAreasHeading = noEmptyContent.indexOf('Learning areas')
    console.log(noEmptyContent)
    console.log(idxOfSummaryHeading)
    console.log(idxOfLearningAreasHeading)
    return idxOfLearningAreasHeading - idxOfSummaryHeading > 1;
}


const activeHowToUseTechItem = computed(() => {
    // handle when techLandscape is empty or has a length of 0
    if (!props.techLandscape || !props.techLandscape.length) {
        return []
    }
    return props.techLandscape.filter(htuitem => props.techUsed.map(tuItem => tuItem.name).includes(htuitem.name)).filter(techLandscapeItem => howToUseTechHasContent(techLandscapeItem.text))
})

const selectedIndex = ref(0)
const galleryKey = ref(0)

const handleClickTechItem = (index) =>{
    selectedIndex.value = index
    galleryKey.value++
}

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
        <div
            v-if="activeHowToUseTechItem.length"
            class="flex flex-col"
        >
            <div class="TechItemRow flex flex-row gap-4 px-10">
                <div
                    v-for="(landscapeItem, index) in activeHowToUseTechItem"
                    :key="index"
                    class="border-[1px] cursor-pointer px-6 py-2 rounded hover:!bg-main-teal hover:!text-white"
                    :class="{'bg-main-teal text-white': selectedIndex === index }"
                    @click="() => handleClickTechItem(index)"
                >
                    {{ displayTitle(landscapeItem.name) }}
                </div>
            </div>
            <div
                class="EachTechItemRow flex flex-row px-10 py-4 w-full"
            >
                <div class="flex flex-col textColumn w-full  lg:!w-1/2">
                    <div class="font-semibold pt-4 text-2xl">
                        {{ displayTitle(activeHowToUseTechItem[selectedIndex].name) }}
                    </div>
                    <div
                        class="richTextContentContainer text"
                        v-html="activeHowToUseTechItem[selectedIndex].text"
                    />
                </div>
                <div class="flex flex-col imageGallery w-full  lg:!w-1/2">
                    <SchoolHowToUseTechGallery
                        :key="galleryKey"
                        :image-array="activeHowToUseTechItem[selectedIndex].images"
                    />
                </div>
            </div>




            <!--            <div-->
            <!--                v-for="(landscapeItem, index) in activeHowToUseTechItem"-->
            <!--                :key="index"-->
            <!--                class="EachTechItemRow flex flex-row px-10 py-4 w-full   even:bg-slate-50 odd:bg-white"-->
            <!--            >-->
            <!--                <div class="flex flex-col textColumn w-full  lg:!w-1/2">-->
            <!--                    <div class="font-semibold pt-4 text-2xl">-->
            <!--                        {{ displayTitle(landscapeItem.name) }}-->
            <!--                    </div>-->
            <!--                    <div-->
            <!--                        class="richTextContentContainer text"-->
            <!--                        v-html="landscapeItem.text"-->
            <!--                    />-->
            <!--                </div>-->
            <!--                <div class="flex flex-col imageGallery w-full  lg:!w-1/2">-->
            <!--                    <SchoolHowToUseTechGallery :image-array="landscapeItem.images" />-->
            <!--                </div>-->
            <!--            </div>-->
        </div>
        <div
            v-else
            class="flex justify-center items-center text-xl"
        >
            No information has been provided. Please contact your principal.
        </div>
    </div>
</template>
