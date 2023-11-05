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
<!-- <svg class="absolute w-full -top-28 left-0 overflow-clip" width="1800" height="180" fill="none" version="1.1" viewBox="0 0 1800 81" xmlns="http://www.w3.org/2000/svg">
 <path d="m1800.6 25v43c-6.7299 11.807-35.73 38.573-68.027 52.108-11.591 4.8579-11.036 5.3123-35.17 8.1573s-129.49 0.73489-201.06 0.73489h-1496.3v-75h871.86c190.53 0 466.86 
 0 623.47 1 156.62-3 230.44 19.476 305.25-75.524z" 
            fill="url(#paint0_linear_1830_23198)"
        />
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
    </svg> -->
</template>
