<script setup>
const props = defineProps({
    color1:{
        type: String,
        required: false,
        default: "#002858"
    },
    color2:{
        type: String,
        required: false,
        default: "#0A7982"
    },
    color3:{
        type: String,
        required: false,
        default:"#0A7982"
    },
})
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
                    :stop-color="props.color1"
                />
                <stop
                    offset="0.460472"
                    :stop-color="props.color2"
                />
                <stop
                    offset="1"
                    :stop-color="props.color3"
                />
            </linearGradient>
        </defs>
    </svg>
</template>
