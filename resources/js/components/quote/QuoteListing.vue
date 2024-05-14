<script setup>
import {computed,ref} from 'vue'

const props = defineProps({
    quoteVendor:{
        type:String,
        required: true
    },
    quoteItems: {
        type:Array,
        required: true
    }
})

const emits = defineEmits([])
</script>

<template>
    <div> Hello</div>
</template>
