<script setup>
import {computed,ref} from 'vue'

const props = defineProps({
    priceValue:{
        type: Number,
        required:true
    },
    displaySize:{
        type: String,
        required: false,
        default: 'normal'
    },
    includeGst:{
        type: Boolean,
        required: false,
        default: false
    },
    showIncGst :{
        type: Boolean,
        required: false,
        default: true
    }
})

const extGstPrice = computed(() =>{
    if(!props.includeGst) return props.priceValue.toFixed(2)
    else{
        return (props.priceValue - (props.priceValue / 11)).toFixed(2)
    }
})
const incGstPrice = computed(() =>{
    if(props.includeGst) return props.priceValue.toFixed(2)
    else{
        return (props.priceValue * 1.1).toFixed(2)
    }
})
const textSizeClass = computed(() =>{
    if(props.displaySize === 'large'){
        return 'text-3xl'
    } else if(props.displaySize === 'small')
        return 'text-base'
    else{
        return 'text-lg'
    }
})
</script>

<template>
    <div class="flex flex-col">
        <div
            class="extGstPriceDisplay"
            :class="textSizeClass"
        >
            {{ "$" + extGstPrice }} <span class="text-slate-600"> exc. GST</span>
        </div>
        <div
            v-if="props.showIncGst"
            class="incGstPriceDisplay text-slate-600"
            :class="textSizeClass"
        >
            {{ "$" + incGstPrice + " inc. GST" }}
        </div>
    </div>
</template>
