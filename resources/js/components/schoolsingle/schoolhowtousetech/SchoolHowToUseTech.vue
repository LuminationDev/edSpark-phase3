<script setup>
import {watchDebounced} from "@vueuse/core";
import {computed, ref} from 'vue'

import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import ImageUploaderInput from "@/js/components/bases/ImageUploaderInput.vue";
import SchoolHowToUseTechEditableRow
    from "@/js/components/schoolsingle/schoolhowtousetech/SchoolHowToUseTechEditableRow.vue";
import {schoolPartnerTech, schoolTech} from "@/js/constants/schoolTech";

// techUsed is the technology selected on the selector
// techLandscape is the content being saved to the profile
const props = defineProps({
    techUsed: {
        type: Array,
        required: true
    },
    techLandscape: {
        type: Array,
        required: true
    }
})

const defaultTextTemplate = "<h3 dir=\"ltr\">Summary</h3>\n<p dir=\"ltr\">&nbsp;</p>\n<h3 dir=\"ltr\">Learning areas</h3>\n<ul>\n<li dir=\"ltr\" aria-level=\"1\">\n<p dir=\"ltr\" role=\"presentation\">&nbsp;</p>\n</li>\n<li dir=\"ltr\" aria-level=\"1\">\n<p dir=\"ltr\" role=\"presentation\">&nbsp;</p>\n</li>\n</ul>\n<h3 dir=\"ltr\">Learning outcomes</h3>\n<ul>\n<li dir=\"ltr\" aria-level=\"1\">\n<p dir=\"ltr\" role=\"presentation\">&nbsp;</p>\n</li>\n<li dir=\"ltr\" aria-level=\"1\">\n<p dir=\"ltr\" role=\"presentation\">&nbsp;</p>\n</li>\n</ul>\n<h3 dir=\"ltr\">Year levels</h3>\n<ul>\n<li dir=\"ltr\" aria-level=\"1\">\n<p dir=\"ltr\" role=\"presentation\">&nbsp;</p>\n</li>\n</ul>\n<h3 dir=\"ltr\">Relevant curriculum codes</h3>\n<p dir=\"ltr\">(Curriculum code - Curriculum name)</p>\n<p>&nbsp;</p>"

// create data structure
const createHowToDataStructure = item => {
    return ({
        name: item.name,
        text: defaultTextTemplate,
        images: []
    })
}
const howToUseData = ref([])
const allTechnologyList = [...schoolTech, ...schoolPartnerTech]


// initialise howToUseData,
// if not null, deep copy and check if there's missing field and create it with the createHowTODataStructure
// if null or length == 0, make a fresh copy
if (props.techLandscape && props.techLandscape.length > 0) {
    howToUseData.value = _.cloneDeep(props.techLandscape)
    howToUseData.value.forEach(existingItem => {
        const matchingTech = allTechnologyList.find(tech => tech.name === existingItem.name);
        if (!matchingTech) {
            // If the item is missing in allTechnologyList, add it
            howToUseData.value.push(createHowToDataStructure(existingItem));
        }
    });
} else {
    howToUseData.value = allTechnologyList.map(item => {
        return createHowToDataStructure(item);
    })
}

const handleTinyMceContent = (name, data) => {
    howToUseData.value.filter(item => item.name === name)[0].text = data
    console.log(name)
    console.log(howToUseData.value.filter(item => item.name === name)[0])
}
const handleUploadedImageUrls = (name, urlsData) => {
    console.log(name)
    console.log(howToUseData.value.filter(item => item.name === name)[0])
    howToUseData.value.filter(item => item.name === name)[0].images = urlsData.map(item => item.remoteUrl)
}

const activeHowToUseTechItem = computed(() => {
    return howToUseData.value.filter(htuitem => props.techUsed.map(tuItem => tuItem.name).includes(htuitem.name))
})


const emits = defineEmits(['emitTechLandscape'])


const sendDataToParent = () =>{
    emits('emitTechLandscape', howToUseData.value)
}

watchDebounced(howToUseData, () =>{
    sendDataToParent()
}, {deep: true})

</script>

<template>
    <div class="HowToUseTechContainer flex justify-center items-center flex-col px-5 py-2 text-genericDark">
        <div
            v-for="(item,index) in activeHowToUseTechItem"
            :key="index"
            class="HowToUseItem border-[1px] flex justify-center flex-col mt-6 p-4 rounded w-full"
        >
            <SchoolHowToUseTechEditableRow
                :how-to-use-tech-item="item"
                @emit-tiny-mce-content="(name,data) => handleTinyMceContent(name,data)"
                @emit-images-array="(name, urls) => handleUploadedImageUrls(name,urls)"
            />
            <!--            <div class="HowToUseText richTextContentContainer w-full">-->
            <!--                <div class="font-semibold howToTitle mb-6 text-xl">-->
            <!--                    {{ displayTitle(item.name) }}-->
            <!--                </div>-->
            <!--                <TinyMceRichTextInput-->
            <!--                    :src-content="item.text"-->
            <!--                    @emit-tiny-rich-content="(data) => handleTinyMceContent(item.name,data)"-->
            <!--                />-->
            <!--            </div>-->
            <!--            <div class="HowToUseImage flex justify-center items-center flex-col h-full mt-2 w-full">-->
            <!--                <div class="flex mb-4 self-start">-->
            <!--                    Image gallery (Up to 5 images)-->
            <!--                </div>-->
            <!--                <ImageUploaderInput-->
            <!--                    :item-type="'HowToUseTech'"-->
            <!--                    :current-media="item.images"-->
            <!--                    :max="5"-->
            <!--                    @emit-uploaded-media="(urlsArray)=>handleUploadedImageUrls(item.name, urlsArray)"-->
            <!--                />-->
            <!--            </div>-->
        </div>
    </div>
</template>
