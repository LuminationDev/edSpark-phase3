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
        width="1800"
        height="180"
        viewBox="0 0 1800 81"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        class="absolute w-full -top-28 left-0 overflow-clip"
    >
        <path
            d="M 1805 -3 V 22 V 40 C 1793 61 1727 101 1716 101 C 1573 101 1583 101 1500 101 H 0 V 26 H 874 C 1065 26 1342 26 1499 27 C 1656 24 1730 21 1805 -74 Z"
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
                    :stop-color="(useCustomColor ? schoolColorTheme[colorTheme]['dark'] :'#0d47a1')"
                />
                <stop
                    offset="0.460472"
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
