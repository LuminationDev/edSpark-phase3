<script setup>
import purify from 'dompurify';
import {computed} from 'vue'

import {findNestedKeyValue, safelyExtractFirstObjectFromArray} from "@/js/helpers/objectHelpers";
import {partnerService} from "@/js/service/partnerService";


const props = defineProps({
    data: {
        type: Object,
        required: false,
        default: () => {
        }
    },
    contentFromBase: {
        type: Object,
        required: true,
    },
    recommendationFromBase: {
        type: Object,
        required: true
    }
})

const handleClickSubmitButton = () =>{
    return partnerService.updatePartnerContent(65,65, {data: 'haha'}).then(res =>{
        console.log('update content has been called')
    })
}


const emits = defineEmits([])


</script>

<template>
    <div class="PartnerOverviewContainer">
        <div class="partnerOverviewRichContentRender w-full lg:!w-2/3">
            <div class="font-semibold text-2xl" />
            <!--            <div-->
            <!--                class="my-6"-->
            <!--                v-html="purify.sanitize(parsedOverviewContent['content'])"-->
            <!--            />-->
            <pre> {{ contentFromBase }}</pre>
            <button @click="handleClickSubmitButton">
                test update sned
            </button>
        </div>
    </div>
</template>
<style scoped>

:deep(p) {
    margin-top: 16px;
    text-align: justify;
}
</style>




