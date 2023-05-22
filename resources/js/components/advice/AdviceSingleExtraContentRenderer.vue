<script setup>
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

contentType = Object.keys(props.content['data']['extra_content'])[0]
/**
 * itemArray = Array<{
 *     content: string-html
 *     heading: string
 *     icon: string-icon-name
 * }>
 */
const itemArray = computed(()=> {
    return props.content['data']['extra_content'][contentType]['item']
})




</script>

<template>
    <div class="extraContentRendererContainer flex flex-col py-2 my-2 px-4">
        <template v-if="contentType == 'numbereditems'">
            <AdviceNumberedListRenderer :item-array="itemArray" />
        </template>
        <template v-else-if="contentType == 'iconitems'">
            <AdviceIconListRenderer :item-array="itemArray" />
        </template>
        <template v-else-if="contentType == 'numberedicontems'">
            <AdviceNumberedIconListRenderer :item-array="itemArray" />
        </template>
        <template v-else>
            <div v-html="itemArray" />
        </template>
    </div>
</template>

