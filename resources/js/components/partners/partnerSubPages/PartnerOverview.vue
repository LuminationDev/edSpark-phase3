<script setup>
import {ref, computed} from 'vue'
import {findNestedKeyValue, isObjectEmpty, safelyExtractFirstObjectFromArray} from "@/js/helpers/objectHelpers";
import purify from 'dompurify';


const props = defineProps({
    data:{
        type: Object,
        required: false,
        default: () => {}
    },
    contentFromBase:{
        type: Object,
        required: true,
    },
    recommendationFromBase:{
        type: Object,
        required: true
    }
})

const parsedOverviewContent = computed(()=> {
    if(props.contentFromBase?.content){
        const allContent = JSON.parse(props.contentFromBase.content)
        let overviewContent = findNestedKeyValue(allContent, 'content').filter(content => typeof content === 'string')
        try {
            overviewContent = safelyExtractFirstObjectFromArray(overviewContent)
            if(!overviewContent) {
                console.log('overview content is not formatted properly')
            }
        } catch(e){
            console.log(e.message)
        }

        let overviewHeading = findNestedKeyValue(allContent, 'heading')
        try {
            overviewHeading = safelyExtractFirstObjectFromArray(overviewHeading)
            if(!overviewHeading) {
                console.log('overview content is not formatted properly')
            }
        } catch(e){
            console.log(e.message)
        }

        return {heading: overviewHeading, content: overviewContent}
    } else{
        return { content : '', heading: ''}
    }
})

const emits = defineEmits([])
</script>

<template>
    <div class="PartnerOverviewContainer">
        <div class="partnerOverviewRichContentRender w-full lg:!w-2/3">
            <div class="font-semibold text-2xl">
                {{ parsedOverviewContent['heading'] }}
            </div>
            <div
                class="my-6"
                v-html="purify.sanitize(parsedOverviewContent['content'])"
            />
        </div>
    </div>
</template>
<style scoped>

:deep(p){
    margin-top: 16px;
    text-align: justify;
}
</style>

