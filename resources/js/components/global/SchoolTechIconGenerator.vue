<script setup>

import {ref} from "vue";
import BaseIconGenerator from "@/js/components/global/BaseIconGenerator.vue";

const props = defineProps({
    techName:{
        type: String,
        required: true
    },
    // toggleTooltip: {
    //     type: Boolean,
    //     required: false
    // }
})
const iconPath = ref('')

const schoolTechIconPaths = {
    "Microsoft Teams": "/components/svg/Microsoft.vue",
    "3D Printing": '/components/svg/ThreeDPrintingIcon.vue',
    "Apple": '/components/svg/AppleIcon.vue',
    "Frog":'/components/svg/FrogIcon.vue',
    "IoT":'/components/svg/IoTIcon.vue',
    "Robotics":'/components/svg/RoboticsIcon.vue',
    "AR":'/components/svg/ARIcon.vue',
    "VR":'/components/svg/VRIcon.vue',
    "Lumination":'/components/svg/LuminationIcon.vue',
    "Makers Empire":'/components/svg/MakersEmpireIcon.vue'
}

if(Object.keys(schoolTechIconPaths).includes(props.techName)){
    iconPath.value = schoolTechIconPaths[`${props.techName}`]
}

</script>
<template>
    <template v-if="iconPath">
        <BaseIconGenerator :icon-path="iconPath" />
    </template>
</template>
