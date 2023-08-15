*-<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";
import { computed} from "vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {axiosFetcherParams} from "@/js/helpers/fetcher";
import useSWRV from "swrv";


const {data : allCuratedSoftware, error: curatedSoftwareError} = useSWRV(API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS, axiosFetcherParams(useUserStore().getUserRequestParam))

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
        class="bg-purple-50 flex justify-center items-center flex-col px-5 rounded softwareSingleCuratedContentContainer xl:!ml-4 xl:!px-10"
    >
        <div class="curatedResourcesTitle font-bold my-2 py-4 text-2xl text-center uppercase">
            RELATED
        </div>
        <div class="flex-col lg:!flex-row xl:!flex-col">
            <SoftwareCard
                v-for="software in twoRecommendation"
                :key="software.guid"
                :software-data="software"
                :show-icon="true"
                :number-per-row="2"
                class="mb-4"
            />
        </div>
    </div>
    <div v-else>
        Loading
    </div>
</template>