<script setup>
import {shallowRef, watchEffect} from "vue";
import SchoolsTech from "@/js/components/schools/SchoolsTech.vue";
const props = defineProps({
    techName:{
        type: String,
        required: true
    },
    techInfo:{
        type: Object,
        required: true
    }
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
    }
}


import(`../../${getIconPath(props.techName)}.vue`).then(val =>
    techIcon.value = val.default
)

</script>
<template>
    <div class="my-auto group/tech schools-tech">
        <component
            :is="techIcon"
            v-if="techIcon"
        />
        <SchoolsTech :tech-hover="techInfo" />
    </div>
</template>