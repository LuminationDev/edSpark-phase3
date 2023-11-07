<script setup>

import {ref} from "vue";
import BaseIconGenerator from "@/js/components/global/BaseIconGenerator.vue";
import MicrosoftTeamsIcon from '@/js/components/svg/Microsoft.vue';
import ThreeDPrintingIcon from '@/js/components/svg/ThreeDPrintingIcon.vue';
import AppleIcon from '@/js/components/svg/AppleIcon.vue';
import FrogIcon from '@/js/components/svg/FrogIcon.vue';
import IoTIcon from '@/js/components/svg/IoTIcon.vue';
import RoboticsIcon from '@/js/components/svg/RoboticsIcon.vue';
import ARIcon from '@/js/components/svg/ARIcon.vue';
import VRIcon from '@/js/components/svg/VRIcon.vue';
import LuminationIcon from '@/js/components/svg/LuminationIcon.vue';
import MakersEmpireIcon from '@/js/components/svg/MakersEmpireIcon.vue';


const props = defineProps({
    techName:{
        type: String,
        required: true
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
    <BaseIconGenerator
        :icon-path="techName"
        :components-object="schoolTechIconComponents"
    />
</template>
