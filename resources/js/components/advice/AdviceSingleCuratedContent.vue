<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";
import {  computed } from "vue";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import {axiosFetcherParams} from "@/js/helpers/fetcher";
import useSWRV from "swrv";

const userStore = useUserStore()
const {data : allCuratedAdvice, error: curatedAdviceError} = useSWRV(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS, axiosFetcherParams(userStore.getUserRequestParam))
const twoRecommendation  = computed( () => {
    let temp = []
    if(allCuratedAdvice.value){
        for(let i= 0; i < 2 ; i++){
            temp.push(allCuratedAdvice.value[Math.floor(Math.random() * allCuratedAdvice.value.length)])
        }
    }
    return temp
})

</script>
<template>
    <div
        v-if="allCuratedAdvice"
        class="adviceSingleCuratedContentContainer bg-orange-50 flex justify-center items-center flex-col px-5 rounded xl:!ml-4 xl:!px-10"
    >
        <div class="curatedResourcesTitle font-bold my-2 py-8 text-2xl text-center uppercase">
            Other Curated Resources
        </div>
        <div class="flex-col lg:!flex-row xl:!flex-col">
            <AdviceCard
                v-for="advice in twoRecommendation"
                :key="advice.guid"
                :advice-data="advice"
                :show-icon="true"
                :number-per-row="1"
                class="mb-extraLarge"
            />
        </div>
    </div>
    <div v-else>
        Loading
    </div>
</template>
