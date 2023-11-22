<script setup>
import {storeToRefs} from "pinia";

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useAdviceStore} from "@/js/stores/useAdviceStore";

const adviceStore = useAdviceStore()
const {relatedAdvice} = storeToRefs(adviceStore)
</script>
<template>
    <div
        v-if="relatedAdvice && relatedAdvice.length > 0"
        class="
            adviceSingleCuratedContentContainer
            bg-secondary-coolGrey/20
            flex
            justify-center
            items-center
            flex-col
            px-4
            py-10
            rounded
            xl:!ml-4
            xl:!px-10
            "
    >
        <div class="curatedResourcesTitle font-bold pb-8 text-2xl text-center">
            Other curated resources
        </div>
        <div class="flex flex-col gap-10 lg:!flex-row xl:!flex-col">
            <AdviceCard
                v-for="advice in relatedAdvice"
                :key="advice.guid"
                :data="advice"
                :show-icon="true"
            />
        </div>
    </div>
    <div v-else>
        Loading
    </div>
</template>
