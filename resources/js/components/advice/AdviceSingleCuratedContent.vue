<script setup>
import {storeToRefs} from "pinia";

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import {useAdviceStore} from "@/js/stores/useAdviceStore";

const adviceStore = useAdviceStore()
const {relatedAdvice} = storeToRefs(adviceStore)
</script>
<template>
    <div
        v-if="relatedAdvice && relatedAdvice.length > 0"
        class="adviceSingleCuratedContentContainer flex flex-col py-6 rounded"
    >
        <div class="curatedResourcesTitle font-bold pb-4 text-2xl text-start">
            Other similar resources
        </div>
        <div class="flex flex-row gap-10">
            <AdviceCard
                v-for="advice in relatedAdvice"
                :key="advice.guid"
                :data="advice"
                :show-icon="true"
            />
        </div>
    </div>
    <div v-else>
        Loading..
    </div>
</template>
