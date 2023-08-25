<script setup>
import AdviceExtraResourcesRenderer from "@/js/components/advice/Renderer/AdviceExtraResourcesRenderer.vue";
import ExtraResourceRenderer from "@/js/components/renderer/ExtraResourceRenderer.vue";
import ExtraResourceTemplateRenderer from "@/js/components/renderer/ExtraResourceTemplateRenderer.vue";
import NumberedListRenderer from "@/js/components/renderer/NumberedListRenderer.vue";
import {findNestedKeyValue} from "@/js/helpers/objectHelpers";
import {ref, computed} from 'vue'

const props = defineProps({
    content: {
        type: Array,
        required: true
    }
})


const templateItems = computed(() => {
    return props.content.filter(item =>{
        return item.type === 'templates' && item.data.template;
    })
})

const extraResourceItems = computed(() => {
    return props.content.filter(item =>{
        return item.type === 'extra_resources' && (item.data.item[0].heading || item.data.item[0].content)
    })
})
//numberedItems Template
const numberedItemsArray = computed(() =>{
    let key = `numbereditems`
    const arrayOfNumberedItemsData = findNestedKeyValue(templateItems.value, key)
    if(arrayOfNumberedItemsData && arrayOfNumberedItemsData.length){
        return arrayOfNumberedItemsData
    } else return []
})

// extraResourceTemplate - need to be removed in the future
const extraResourceTemplateArray = computed(() =>{
    let key = `extraresource`
    const arrayOfNumberedItemsData = findNestedKeyValue(templateItems.value, key)
    if(arrayOfNumberedItemsData && arrayOfNumberedItemsData.length){
        return arrayOfNumberedItemsData
    } else return []
})

// bit wanky, need to redo with hardware resources inside filament
// will refactor once we are working on back there
const hardwareExtraResourceArray = computed(()=>{
    let key = `hardware_extra_resource`
    const arrayOfNumberedItemsData = findNestedKeyValue(templateItems.value, key).filter(item =>{
        let firstKey = Object.keys(item.item)[0]
        let resourceValuesArr = Object.values(item.item[firstKey])
        let shallReturn = false
        // check if any of the value is not null, we will return and render them
        for(const eachValue of resourceValuesArr){
            if(eachValue) shallReturn = true
        }
        return shallReturn
    })
    if(arrayOfNumberedItemsData && arrayOfNumberedItemsData.length){
        return arrayOfNumberedItemsData
    } else return []
})


// implement other typeif templates here
// Plain extra resources
const extraResourcesArray = computed(() => {
    let key = 'item'
    const arrayOfExtraResourcesForRenderer = findNestedKeyValue(extraResourceItems.value,key)
    if(arrayOfExtraResourcesForRenderer &&  arrayOfExtraResourcesForRenderer.length){
        return arrayOfExtraResourcesForRenderer
    } else return []
})
</script>

<template>
    <template v-if="numberedItemsArray && numberedItemsArray.length">
        <div
            v-for="(item, index) in numberedItemsArray"
            :key="index "
        >
            <NumberedListRenderer
                :item-array="item.item"
            />
        </div>
    </template>
    <template v-if="hardwareExtraResourceArray && hardwareExtraResourceArray.length">
        <div
            v-for="(item, index) in hardwareExtraResourceArray"
            :key="index "
        >
            <NumberedListRenderer
                :item-array="item.item"
            />
        </div>
    </template>
    <template v-if="extraResourceTemplateArray && extraResourceTemplateArray.length">
        <div
            v-for="(resource,index) in extraResourceTemplateArray"
            :key="index"
        >
            <ExtraResourceTemplateRenderer :item-array="resource" />
        </div>
    </template>
    <template v-if="extraResourceItems && extraResourceItems.length">
        <div
            v-for="(resource,index) in extraResourcesArray"
            :key="index"
        >
            <ExtraResourceRenderer :item-array="resource" />
        </div>
    </template>
</template>
