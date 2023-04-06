<script setup>

import {shallowRef} from "vue";
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


const techIcon = shallowRef('')

const getIconPath = (iconType) => {
    switch(iconType){
    case "Microsoft Teams":
        return '/components/svg/Microsoft.vue'
    case "3D Printing":
        return '/components/svg/ThreeDPrintingIcon.vue'
    case "Apple":
        return '/components/svg/AppleIcon.vue'
    case "Frog":
        return '/components/svg/FrogIcon.vue'
    case "IoT":
        return '/components/svg/IoTIcon.vue'
    case "Robotics":
        return '/components/svg/RoboticsIcon.vue'
    case "AR":
        return '/components/svg/ARIcon.vue'
    case "VR":
        return '/components/svg/VRIcon.vue'
    case "Lumination":
        return '/components/svg/LuminationIcon.vue'
    case "Makers Empire":
        return '/components/svg/MakersEmpireIcon.vue'
    }
}

import(`../../${getIconPath(props.techName)}`).then(val =>
    techIcon.value = val.default
)


</script>
<template>
    <component
        :is="techIcon"
        v-if="techIcon"

    />
</template>
