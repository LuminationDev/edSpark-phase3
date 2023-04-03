<script setup>
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";
import {ref } from 'vue'

const props = defineProps({
    colorTheme:{
        type: String,
        required: false
    }
})
const useCustomColor= ref(false)
/**
 * Check if the color passed in is a part of supported color
 */
if(schoolColorKeys.includes(props.colorTheme)){
    useCustomColor.value = true
}
</script>

<template>
    <svg
        viewBox="0 0 1440 229"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path
            d="M1440 0V183.073C1440 183.073 1421.83 199.963 1332 217.073C1279.5 227.073 1199.5 228.986 1199.5 228.986H0V157.073H700.5C700.5 157.073 855 157.073 1066 157.073C1277 157.073 1384.5 102 1440 0Z"
            fill="url(#paint0_linear_539_14057)"
        />
        <defs>
            <linearGradient
                id="paint0_linear_539_14057"
                x1="1911"
                y1="87.0727"
                x2="471"
                y2="465.073"
                gradientUnits="userSpaceOnUse"
            >
                <stop
                    offset="0.217599"
                    :stop-color="(useCustomColor ? schoolColorTheme[colorTheme]['dark'] :'#0d47a1')"
                />
                <stop
                    offset="0.640707"
                    :stop-color="(useCustomColor ? schoolColorTheme[colorTheme]['med'] :'#1e88e5')"
                />
                <stop
                    offset="1"
                    :stop-color="(useCustomColor ? schoolColorTheme[colorTheme]['light'] : '#64b5f6')"
                />
            </linearGradient>
        </defs>
    </svg>
</template>
