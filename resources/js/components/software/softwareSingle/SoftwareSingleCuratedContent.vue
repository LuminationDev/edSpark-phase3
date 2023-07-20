*-<script setup>
import { computed} from "vue";
import {serverURL} from "@/js/constants/serverUrl";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {axiosFetcher} from "@/js/helpers/fetcher";
import useSWRV from "swrv";


const {data : allCuratedSoftware, error: curatedSoftwareError} = useSWRV(`${serverURL}/fetchSoftwarePosts`, axiosFetcher)

const twoRecommendation  = computed( () => {
    let temp = []
    if(allCuratedSoftware.value){
        for(let i= 0; i < 2 ; i++){
            temp.push(allCuratedSoftware.value[Math.floor(Math.random() * allCuratedSoftware.value.length)])
        }
    }
    return temp
})

</script>
<template>
    <div
        v-if="allCuratedSoftware && allCuratedSoftware.length > 0"
        class="softwareSingleCuratedContentContainer flex flex-col justify-center items-center ml-4 px-10 rounded bg-purple-50"
    >
        <div class="curatedResourcesTitle uppercase font-bold text-2xl text-center py-4 my-2 ">
            RELATED
        </div>
        <SoftwareCard
            v-for="(software,index) in twoRecommendation"
            :key="index"
            :software="software"
            :show-icon="true"
            :number-per-row="1"
            class="mb-4"
        />
    </div>
    <div v-else>
        Loading
    </div>
</template>