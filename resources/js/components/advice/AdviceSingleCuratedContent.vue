<script setup>
import { ref, computed, onMounted} from "vue";
import {serverURL} from "@/js/constants/serverUrl";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import {axiosFetcher} from "@/js/helpers/fetcher";
import useSWRV from "swrv";


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
        class="adviceSingleCuratedContentContainer flex flex-col justify-center items-center ml-4 px-10 rounded bg-orange-50"
    >
        <div class="curatedResourcesTitle uppercase font-bold text-2xl text-center py-8 my-2">
            Other Curated Resources
        </div>
        <AdviceCard
            v-for="(advice,index) in twoRecommendation"
            :key="index"
            :advice-content="advice"
            :show-icon="true"
            :number-per-row="1"
            class="mb-extraLarge"
        />
    </div>
    <div v-else>
        Loading
    </div>
</template>
