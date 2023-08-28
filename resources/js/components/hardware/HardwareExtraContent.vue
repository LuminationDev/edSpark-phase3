<script setup>
import ExtraResourceTemplateDisplay from "@/js/components/renderer/ExtraResourceTemplateDisplay.vue";
import NumberedListRenderer from "@/js/components/renderer/NumberedListRenderer.vue";
import {computed} from 'vue'
import AdviceNumberedListRenderer from "@/js/components/advice/Renderer/AdviceNumberedListRenderer.vue";
import AdviceIconListRenderer from "@/js/components/advice/Renderer/AdviceIconListRenderer.vue";
import AdviceNumberedIconListRenderer from "@/js/components/advice/Renderer/AdviceNumberedIconListRenderer.vue";
const props  = defineProps({
    content:{
        type: Object,
        required: true
    }
})
// get contentType from somewhere
let contentType = 'numbereditems'
// console.log(Object.keys(props.content['data']['extra_content'])[0])
// can use Object.keys(props.content['data']['extra_content']) to get all the keys
// props.content['data']['template']
// console.log(props.content['data']['template'].split("\\")[props.content['data']['template'].split("\\").length - 1].toLowerCase() )

// contentType = Object.keys(props.content['data']['extra_content'])[0]
// contentType = 'hardware_extra_resource'
/**
 * itemArray = Array<{
 *     content: string-html
 *     heading: string
 *     icon: string-icon-name
 * }>
 */
const itemArray = computed(()=> {
    return props.content['data']['extra_content']['hardware_extra_resource']['item']
})

// Boolean flag that return true or false whether to render extra resources.
// if any of the field of the extra resources === null, we will skip
const shouldRenderResources  = computed(() => {
    if(Object.keys(itemArray.value).length === 0 ) return false
    else {
        let extraResourceKey = Object.keys(itemArray.value)[0] // should get a hash of the first item, only one item
        let innerItemKeysArr = Object.keys(itemArray.value[extraResourceKey])
        for(const eachKey of innerItemKeysArr){
            if(itemArray.value[extraResourceKey][eachKey] === null){
                return false
            }
        }
        return true
    }
})




</script>

<template>
    <div
        v-if="shouldRenderResources"
        class="extraContentRendererContainer flex flex-col my-2 px-4 py-2"
    >
        <template v-if="contentType === 'numbereditems'">
            <NumberedListRenderer :item-array="itemArray" />
        </template>
        <template v-else-if="contentType === 'iconitems'">
            <AdviceIconListRenderer :item-array="itemArray" />
        </template>
        <template v-else-if="contentType === 'numberedicontems'">
            <AdviceNumberedIconListRenderer :item-array="itemArray" />
        </template>
        <template v-else>
            <div
                v-for="(key,index) in Object.keys(itemArray)"
                :key="index"
            >
                <div v-html="itemArray[key]['content']" />
            </div>
        </template>
    </div>
</template>

