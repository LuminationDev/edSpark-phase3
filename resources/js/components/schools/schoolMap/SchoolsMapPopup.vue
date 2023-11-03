<script setup>

import Close from '../../svg/Close.vue';
import SchoolCardIconList from '../SchoolCardIconList.vue';
import OpenLink from "@/js/components/svg/OpenLink.vue";
import { imageURL } from "@/js/constants/serverUrl";
import { ref } from "vue";

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

function getBlurb(mapPopupInfo) {

    if (mapPopupInfo.content_blocks != null && mapPopupInfo.content_blocks.blocks.length > 0) {
        return mapPopupInfo.content_blocks.blocks[1].data.text;
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
// const showFirstTech = ref(true)
// const handleMouseEnterCard = () => {
//     showFirstTech.value = false
// }
//
// const handleMouseExitCard = () =>{
//     showFirstTech.value = true
// }
</script>

<template>
    <div class="flex flex-col mapOuterContainer "
        :style="`
            background-color: rgba(255,255,255,0.85);
            background-image: url('${imageURL}/${props.schoolData?.cover_image ?? ''}'); 
            background-size: cover ;background-blend-mode: screen; `">

        <div class="flex justify-between flex-col p-4 pb-0 place-items-center relative  
                    h-[180px] overflow-hidden">
            <h3 class="font-medium text-xl mt-4 cursor-pointer group
            hover:text-secondary-blue"  @click="handleEmit">
                {{ mapPopupName }} 
                <OpenLink class=" overflow-visible
                        inline align-sub 
                        group-hover:[&>path]:stroke-secondary-blue"/>
            </h3>
            <div class="school-card-body cardDisplayPreview line-clamp text-left p-0 pt-4"
            style="font-size: 0.95rem; line-height:1.5">
                {{ getBlurb(mapPopupInfo) }}
            </div>
        </div>
        <div ref="mapPopup" 
            class="
                    flex 
                    flex-col 
                    mapPopupContent 
                    overflow-scroll 
                    p-4 pt-2 w-[340px]
                    fadebg">
            <!-- flex flex-row gap-6 h-[180px] -->
            <div class="
                    flex
                    flex-wrap
                    justify-center
                    items-center
                    w-full
                    gap-2
                    h-[90px]
                    smaller-icons
            ">
                <SchoolCardIconList 
                    :tech-list="mapPopupInfo.tech_used" 
                    :show-first-tech="showFirstTech" />
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

.fadebg {
    z-index: 1;
    background-color: white;
    box-shadow: 0px -5px 10px 5px rgba(255,255,255,1);
    -webkit-box-shadow: 0px -5px 10px 5px rgba(255,255,255,1);
    -moz-box-shadow: 0px -5px 10px 5px rgba(255,255,255,1);
}

.line-clamp {
  /* width: 400px; */
  margin:0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: initial;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
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