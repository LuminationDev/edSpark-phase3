<script setup>
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";
import {ref } from 'vue'

const props = defineProps({
    colorTheme:{
        type: String,
        required: true,
        default: 'teal'
    }
})
const useCustomColor= ref(false)
/**
 * Check if the color passed in is a part of supported color
 */
if(schoolColorKeys.includes(props.colorTheme)){
    useCustomColor.value = true
} else {

}
</script>

<template>
<svg class="absolute w-full h-fill min-h-8" width="100%" height="100%" fill="none" preserveAspectRatio="none" version="1.1" viewBox="0 0 1800 60" xmlns="http://www.w3.org/2000/svg">
    <rect width="100%" height="100%" fill="url(#paint0_linear_1830_23198)"></rect>
        <defs>
            <linearGradient
                id="paint0_linear_1830_23198"
                x1="1800"
                y1="58"
                x2="709.619"
                y2="969.697"
                gradientUnits="userSpaceOnUse"
            >
                <stop
                    offset="0.214873"
                    :stop-color="(useCustomColor ? schoolColorTheme[colorTheme]['dark'] : schoolColorTheme['teal']['dark'])"
                />
                <stop
                    offset="0.460472"
                    :stop-color="(useCustomColor ? schoolColorTheme[colorTheme]['med'] : schoolColorTheme['teal']['med'])"
                />
                <stop
                    offset="1"
                    :stop-color="(useCustomColor ? schoolColorTheme[colorTheme]['light'] : schoolColorTheme['teal']['light'])"
                />
            </linearGradient>
        </defs>
    </svg>
</template>
