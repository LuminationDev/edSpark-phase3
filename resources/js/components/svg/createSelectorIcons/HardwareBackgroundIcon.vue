<script setup>
import {computed,ref} from 'vue'

const props = defineProps({

})

const emits = defineEmits([])
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        class="icon icon-tabler icon-tabler-devices"
        width="100"
        height="100"
        viewBox="0 0 24 24"
        stroke-width="2"
        stroke="currentColor"
        fill="none"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path
            stroke="none"
            d="M0 0h24v24H0z"
            fill="none"
        />
        <path d="M13 9a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1v-10z" />
        <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h9" />
        <path d="M16 9h2" />
    </svg>
</template>
