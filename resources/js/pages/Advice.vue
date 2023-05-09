<script setup>
import AdviceHero from '../components/advice/AdviceHero.vue'
import {onBeforeMount, ref, computed} from "vue";
import useSWRV from "swrv";

import SectionHeader from "@/js/components/global/SectionHeader.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";
import EducatorHero from "@/js/components/advice/EducatorHero.vue";
import PartnerHero from "@/js/components/advice/PartnerHero.vue";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import {serverURL} from "@/js/constants/serverUrl";
import {axiosFetcher} from "@/js/helpers/fetcher";
import {useRouter} from "vue-router";
import axios from "axios";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";

const router = useRouter()
const {data: allAdvice, error: adviceError} = useSWRV(`${serverURL}/fetchAdvicePosts`, axiosFetcher)



const adviceDAG = computed(() => {
    if(allAdvice.value){
        return allAdvice.value.filter(advice => advice['advice_type'].includes('D.A.G advice'))
    }else{
        return []
    }
})

const adviceEducator = computed(() => {
    if(allAdvice.value){
        return allAdvice.value.filter(advice => advice['advice_type'].includes('Your Classroom') || advice['advice_type'].includes('Your Work')  || advice['advice_type'].includes('Your Learning'))
    }else{
        return []
    }
})

const advicePartner = computed(() => {
    if(allAdvice.value){
        return allAdvice.value.filter(advice => advice['advice_type'].includes('Partner'))
    }else{
        return []
    }
})

const handleBrowseAllAdvice = () => {
    router.push('/browse/advice')
}
</script>

<template>
    <!-- <SectionHeader
        :classes="'bg-[#002858] !'"
        :section="'advice'"
        :title="'Advice'"
        :buttonText="'Browse all resources'"
    /> -->
    <AdviceHero />
    <div class="DAGAdviceRow AdviceContentContainer flex flex-col h-full px-20">
        <div
            v-if="adviceDAG"
            class="AdviceCardListContainer heading text-xl pt-10 flex flex-row flex-1 justify-between flex-wrap  gap-6"
        >
            <AdviceCard
                v-for="(advice, index) in adviceDAG.slice(0,3)"
                :key="index"
                :advice-content="advice"
                :show-icon="true"
            />
        </div>
        <div v-else>
            <Spinner />
        </div>
    </div>
    <EducatorHero />
    <div
        v-if="adviceEducator"
        class="EducatorsAdviceRow AdviceCardListContainer heading text-xl pt-10 flex flex-row flex-wrap justify-between gap-2 flex-1 w-full px-20"
    >
        <AdviceCard
            v-for="(advice, index) in adviceEducator.slice(0,6)"
            :key="index"
            :advice-content="advice"
            :number-per-row="3"
            :show-icon="true"
        />
    </div>
    <div v-else>
        <Spinner />
    </div>
    <PartnerHero />
    <div
        v-if="advicePartner"
        class="PartnerAdviceRow AdviceCardListContainer heading text-xl pt-10 flex flex-row flex-wrap justify-between gap-4 flex-1 w-full px-20"
    >
        <AdviceCard
            v-for="(advice, index) in advicePartner.slice(0,6)"
            :key="index"
            :advice-content="advice"
            :number-per-row="4"
        />
    </div>

    <div v-else>
        <Spinner />
    </div>
</template>
