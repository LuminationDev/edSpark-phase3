<script setup>
import { ref, computed, onMounted} from "vue";
import {serverURL} from "@/js/constants/serverUrl";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import {axiosFetcher} from "@/js/helpers/fetcher";
import useSWRV from "swrv";
import {guid} from "@/js/helpers/guidGenerator";


const {data : allCuratedAdvice, error: curatedAdviceError} = useSWRV(`${serverURL}/fetchAdvicePosts`, axiosFetcher)

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
                v-for="(advice,index) in twoRecommendation"
                :key="index + guid()"
                :advice-content="advice"
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
