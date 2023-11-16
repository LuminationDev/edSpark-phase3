<script setup>

import {ref} from "vue";

import OpenLink from "@/js/components/svg/OpenLink.vue";
import {imageURL} from "@/js/constants/serverUrl";

import Close from '../../svg/Close.vue';
import SchoolCardIconList from '../SchoolCardIconList.vue';

const props = defineProps({
    schoolData: {
        type: Object,
        required: true
    },
    mapPopupName: {
        type: String,
        required: true
    },
    mapPopupInfo: {
        type: Object,
        required: true
    }
});

const stripHTML = (value) => {
    const div = document.createElement('div');
    div.innerHTML = value;
    return div.textContent;
};

function getBlurb(mapPopupInfo) {

    if (mapPopupInfo.content_blocks != null && mapPopupInfo.content_blocks.blocks.length > 0) {
        return stripHTML(mapPopupInfo.content_blocks.blocks[1].data.text);
    } else {
        return "";
    }

}

const emits = defineEmits(['handleLinkToSchool', 'handleToggle']);

const handleClosePopup = () => {
    emits('handleToggle');
};

const handleEmit = () => {
    emits('handleLinkToSchool');
}
</script>

<!-- :style="`
background-color: rgba(255,255,255,0.85);
background-image: url('${imageURL}/${props.schoolData?.cover_image ?? ''}'); 
background-size: cover ;background-blend-mode: screen; `" -->

<template>
    <div class="flex justify-between flex-col mapOuterContainer">
        <div
            class="flex flex-col h-[180px] overflow-hidden p-4 pb-0 place-items-center relative"
        >
            <h3
                class="cursor-pointer font-medium group mt-4 text-xl hover:text-secondary-blue"
                @click="handleEmit"
            >
                {{ mapPopupName }}
                <OpenLink
                    class="align-sub group-hover:[&>path]:stroke-secondary-blue inline overflow-visible"
                />
            </h3>
            <div class="cardDisplayPreview line-clamp p-0 pt-4 school-card-body text-left">
                {{ getBlurb(mapPopupInfo) }}
            </div>
        </div>
        <div
            ref="mapPopup"
            class="flex flex-col mapPopupContent overflow-scroll p-6 pt-2 w-[340px]"
        >
            <!-- flex flex-row gap-6 h-[180px] -->
            <div
                class="fill-secondary-blue flex justify-center items-center flex-wrap gap-2 h-[90px] smaller-icons w-full"
            >
                <SchoolCardIconList
                    :tech-list="mapPopupInfo.tech_used"
                />
            </div>
        </div>
        <!-- <div class="flex justify-end flex-row mb-4 mr-4">
            <button class="bg-[#0072DA] hover:bg-[#0359a9] px-8 py-3 text-white" @click="handleEmit">
                Visit
            </button>
        </div> -->
    </div>
</template>


<style>

.smaller-icons svg {
    width: 35px !important;
    height: 35px !important;
}


.line-clamp {
    font-size: 0.95rem;
    line-height: 1.5;

    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    height: 4.8lh;
    -webkit-box-orient: vertical;
    margin: 0 auto;
}

.mapOuterContainer {
    max-width: 340px;
    max-height: 300px;
}

.mapPopupContent::-webkit-scrollbar {
    display: none !important;
}

.mapPopupContent {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>