<script setup>
import {computed} from 'vue'

const props  = defineProps({
    content:{
        type: Object,
        required: true
    }
})
// get contentType from somewhere
let contentType = 'numbereditems'
// can use Object.keys(props.content['data']['extra_content']) to get all the keys
// props.content['data']['template']
// console.log(props.content['data']['template'].split("\\")[props.content['data']['template'].split("\\").length - 1].toLowerCase() )
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
        <div
            v-for="(item,index) in itemArray"
            :key="index"
            class="eachContent py-2"
        >
            <div class="flex flex-row w-full">
                <div class="extraContentIcon w-1/3 flex justify-center items-center">
                    🔫🏥
                </div>
                <div
                    class="w-2/3"
                    v-html="item.content"
                />
            </div>
        </div>
    </div>
</template>