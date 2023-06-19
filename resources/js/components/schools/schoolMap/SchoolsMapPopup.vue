<script setup>

import Close from '../../svg/Close.vue';
import SchoolCardIconList from '../SchoolCardIconList.vue';

const props = defineProps({
    mapPopupName: {
        type: String,
        required: true
    },
    mapPopupInfo: {
        type: Object,
        required: true
    }
});

const emits = defineEmits(['handleLinkToSchool', 'handleToggle']);

const handleClosePopup = () => {
    emits('handleToggle');
};

const handleEmit = () => {
    emits('handleLinkToSchool');
}
</script>

<template>
    <div
        ref="mapPopup"
        class="p-4 bg-white shadow-xl w-[340px] flex flex-col gap-6 overflow-scroll"
    >
        <div class="relative flex flex-row justify-between place-items-center">
            <h3 class="text-[18px] font-medium">
                {{ mapPopupName }}
            </h3>
        </div>
        <div class="flex flex-row gap-6 h-[180px]">
            <SchoolCardIconList
                :tech-list="mapPopupInfo.tech_used"
            />
        </div>
        <div class="flex flex-row justify-end">
            <button
                class="bg-[#0072DA] text-white px-8 py-3 hover:bg-[#0359a9]"
                @click="handleEmit"
            >
                Visit
            </button>
        </div>
    </div>
</template>
