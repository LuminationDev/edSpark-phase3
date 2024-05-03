<script setup="">
import {computed,ref} from 'vue'

const props = defineProps({
    itemData: {
        type: Object,
        required: true
    },
    showDataLabel:{
        type: Boolean,
        required: false,
        default: false
    }
})

const emits = defineEmits([])
</script>

<template>
    <div> Hello</div>
</template>

