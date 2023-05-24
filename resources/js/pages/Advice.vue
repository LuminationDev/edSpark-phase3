<script setup>
import AdviceHero from '../components/advice/AdviceHero.vue'
import {onBeforeMount, ref, computed, watchEffect} from "vue";
import useSWRV from "swrv";
import useSwrvState from '@/js/helpers/useSWRVState';

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

import CardLoading from '../components/card/CardLoading.vue';

const router = useRouter()
const {data: allAdvice, error: adviceError, isValidating: isValidating} = useSWRV(`${serverURL}/fetchAdvicePosts`, axiosFetcher);
const { state, STATES } = useSwrvState(allAdvice, adviceError, isValidating);
const { data: adviceByType } = useSWRV(`${serverURL}/fetchAdvicePostByType/Partner`);

console.log(adviceByType.value);

console.log(state.value);
console.log(STATES);

const hasLoaded = ref(false);

watchEffect(() => {
    console.log(state.value);
    switch (state) {
        case 'VALIDATING':
                hasLoaded.value = false;
            break;
        case 'PENDING':
                hasLoaded.value = false;
            break;
        case 'SUCCESS':
                hasLoaded.value = true;
            break;

        default:
            break;
    }
});

const adviceDAG = computed(() => {
    if(allAdvice.value){
        return allAdvice.value.filter(advice => advice['advice_type'].includes('D.A.G advice'));
    }else{
        return []
    }
});

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
    <AdviceHero />
    <div class="DAGAdviceRow AdviceContentContainer flex flex-col h-full px-huge">
        <div
            v-if="hasLoaded"
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
            <CardLoading
                :number-per-row="3"
                :number-of-rows="1"
            />
        </div>
    </div>
    <EducatorHero />
    <div
        v-if="hasLoaded"
        class="EducatorsAdviceRow AdviceCardListContainer heading text-xl pt-10 flex flex-row flex-wrap justify-between gap-2 flex-1 w-full px-huge"
    >
        <AdviceCard
            v-for="(advice, index) in adviceEducator.slice(0,6)"
            :key="index"
            :advice-content="advice"
            :number-per-row="3"
            :show-icon="true"
        />
    </div>
    <div
        v-else
        class="px-huge"
    >
        <CardLoading
            :number-per-row="3"
            :number-of-rows="2"
        />
    </div>
    <PartnerHero />
    <div
        v-if="hasLoaded"
        class="PartnerAdviceRow AdviceCardListContainer heading text-xl pt-10 flex flex-row flex-wrap justify-between gap-4 flex-1 w-full px-huge"
    >
        <AdviceCard
            v-for="(advice, index) in advicePartner.slice(0,6)"
            :key="index"
            :advice-content="advice"
            :number-per-row="4"
        />
    </div>

    <div
        v-else
        class="px-huge"
    >
        <CardLoading
            :number-per-row="4"
            :number-of-rows="1"
        />
    </div>
</template>
