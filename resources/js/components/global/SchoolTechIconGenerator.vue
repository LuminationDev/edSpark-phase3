<script setup>

import { ref } from "vue";

import BaseIconGenerator from "@/js/components/global/BaseIconGenerator.vue";
import AppleIcon from '@/js/components/svg/AppleIcon.vue';
import ARIcon from '@/js/components/svg/ARIcon.vue';
import FrogIcon from '@/js/components/svg/FrogIcon.vue';
import IoTIcon from '@/js/components/svg/IoTIcon.vue';
import LuminationIcon from '@/js/components/svg/LuminationIcon.vue';
import MakersEmpireIcon from '@/js/components/svg/MakersEmpireIcon.vue';
import MicrosoftTeamsIcon from '@/js/components/svg/Microsoft.vue';
import RoboticsIcon from '@/js/components/svg/RoboticsIcon.vue';
import ThreeDPrintingIcon from '@/js/components/svg/ThreeDPrintingIcon.vue';
import VRIcon from '@/js/components/svg/VRIcon.vue';


const props = defineProps({
    techName: {
        type: String,
        required: true
    },
    onSchoolCard: {
        type: Boolean,
        required: false,
        default: false
    },
    colorTheme: {
        type: String,
        required: false,
        default: 'partnerBlue',
    }
})

const iconPath = ref('')

const schoolTechIconComponents = {
    "Microsoft Teams": MicrosoftTeamsIcon,
    "Lumination": LuminationIcon,
    "Makers Empire": MakersEmpireIcon,
    "Apple": AppleIcon,
    "Frog": FrogIcon,
    "3D Printing": ThreeDPrintingIcon,
    "IoT": IoTIcon,
    "Robotics": RoboticsIcon,
    "AR": ARIcon,
    "VR": VRIcon
}

</script>

<template>
    <template v-if="!onSchoolCard">
        <BaseIconGenerator
            :icon-path="techName"
            style="padding:4px;"
            class="iconSizer"
            :components-object="schoolTechIconComponents"
        />
    </template>

    <template v-if="onSchoolCard">
        <div
            class="
                !pr-[10px]
                bg-secondary-blueberry/5
                border
                border-secondary-blueberryWeb
                border-solid
                fill-none
                flex
                items-center
                flex-row
                h-[28px]
                min-w-fit
                rounded-full
                schoolCardIcons
                text-secondary-blueberryWeb
                text-sm
                font-light
                w-[200px]
                w-fit
                "
        >
            <BaseIconGenerator
                class="!max-w-[30px] max-h-[25px]"
                :icon-path="techName"
                :components-object="schoolTechIconComponents"
            /> 
            {{ techName }}
        </div>
    </template>
</template>

<style>

.schoolCardIcons path {
    fill: #006ccf !important;
    stroke: transparent !important;
}

@media (max-width: 500px) {
    .iconSizer {
    max-height:50px !important; max-width:50px !important
}

}

</style>


