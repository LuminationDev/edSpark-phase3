<script setup>

import Close from '../../svg/Close.vue';
import SchoolCardIconList from '../SchoolCardIconList.vue';
import {imageURL} from "@/js/constants/serverUrl";
import {ref} from "vue";

const props = defineProps({
    schoolData:{
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

console.log(props.schoolData)

const emits = defineEmits(['handleLinkToSchool', 'handleToggle']);

const handleClosePopup = () => {
    emits('handleToggle');
};

const handleEmit = () => {
    emits('handleLinkToSchool');
}
const showFirstTech = ref(true)
// const handleMouseEnterCard = () => {
//     showFirstTech.value = false
// }
//
// const handleMouseExitCard = () =>{
//     showFirstTech.value = true
// }
</script>

<template>
    <div
        class=" mapOuterContainer flex flex-col"
        :style="`background-color: rgba(255,255,255,0.85); background-image: url('${imageURL}/${props.schoolData['cover_image']}'); background-size: cover ;background-blend-mode: screen; `"
    >
        <div class="relative flex p-4 flex-row justify-between place-items-center">
            <h3 class="text-xl font-medium">
                {{ mapPopupName }}
            </h3>
        </div>
        <div
            ref="mapPopup"
            class="mapPopupContent p-4  w-[340px] flex flex-col gap-6 overflow-scroll"
        >
            <div class="flex flex-row gap-6 h-[180px]">
                <SchoolCardIconList
                    :tech-list="mapPopupInfo.tech_used"
                    :show-first-tech="showFirstTech"
                />
            </div>
        </div>
        <div class="flex flex-row justify-end mb-4 mr-4">
            <button
                class="bg-[#0072DA] text-white px-8 py-3 hover:bg-[#0359a9]"
                @click="handleEmit"
            >
                Visit
            </button>
        </div>
    </div>
</template>
<style>
.mapPopupContent::-webkit-scrollbar {
    display: none !important;
}
.mapPopupContent{
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>