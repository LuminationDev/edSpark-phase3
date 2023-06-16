<script setup>
import AdviceHero from '../components/advice/AdviceHero.vue'
import useSWRV from "swrv";
import useSwrvState from '@/js/helpers/useSwrvState';
import EducatorHero from "@/js/components/advice/EducatorHero.vue";
import PartnerHero from "@/js/components/advice/PartnerHero.vue";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import {serverURL} from "@/js/constants/serverUrl";
import {axiosFetcher} from "@/js/helpers/fetcher";
import {useRouter} from "vue-router";

import CardLoading from '../components/card/CardLoading.vue';

const router = useRouter();

/**
 * Get the DAG Advice articles
 * and states
 */
const { data: dagAdvice, error: dagError, isValidating: dagValidating } = useSWRV(`${serverURL}/fetchAdvicePostByType/D.A.G advice`, axiosFetcher);
const { state: dagState, STATES: DAGSTATES } = useSwrvState(dagAdvice, dagError, dagValidating);

/**
 * Get the Partner advice
 * and states
 */
const { data: partnerAdvice, error: partnerError, isValidating: partnerValidating } = useSWRV(`${serverURL}/fetchAdvicePostByType/Partner`, axiosFetcher);
const { state: partnerState, STATES: PARTNERSTATES } = useSwrvState(partnerAdvice, partnerError, partnerValidating);

/**
 * Get General Advice articles (your work, classroom, learning)
 * and states
 */
const { data: generalAdvice, error: generalError, isValidating: generalValidating } = useSWRV(`${serverURL}/fetchAdvicePostByType/${['Your Classroom', 'Your Work', 'Your Learning']}`, axiosFetcher);
const { state: generalState, STATES: GENERALSTATE } = useSwrvState(generalAdvice, generalError, generalValidating);

const handleBrowseAllAdvice = () => {
    router.push('/browse/advice')
}

</script>

<template>
    <AdviceHero />
    <div class="DAGAdviceRow AdviceContentContainer flex flex-col h-full px-huge">
        <div
            v-if="dagState === 'SUCCESS'"
            class="AdviceCardListContainer heading text-xl pt-10 flex flex-row flex-1 justify-between flex-wrap  gap-6"
        >
            <AdviceCard
                v-for="(advice, index) in dagAdvice.slice(0,3)"
                :key="index"
                :advice-content="advice"
                :show-icon="true"
            />
        </div>
        <div v-else-if="dagState === 'PENDING' || dagState === 'VALIDATING'">
            <CardLoading
                :number-per-row="3"
                :number-of-rows="1"
            />
        </div>
    </div>
    <EducatorHero />
    <div
        v-if="generalState === 'SUCCESS'"
        class="EducatorsAdviceRow AdviceCardListContainer heading text-xl pt-10 flex flex-row flex-wrap justify-between gap-2 flex-1 w-full px-huge"
    >
        <AdviceCard
            v-for="(advice, index) in generalAdvice.slice(0,6)"
            :key="index"
            :advice-content="advice"
            :number-per-row="3"
            :show-icon="true"
        />
    </div>
    <div
        v-else-if="generalState === 'PENDING' || generalState === 'VALIDATING'"
        class="px-huge"
    >
        <CardLoading
            :number-per-row="3"
            :number-of-rows="2"
        />
    </div>
    <PartnerHero />
    <div
        v-if="partnerState === 'SUCCESS'"
        class="PartnerAdviceRow AdviceCardListContainer heading text-xl pt-10 flex flex-row flex-wrap justify-between gap-4 flex-1 w-full px-huge"
    >
        <AdviceCard
            v-for="(advice, index) in partnerAdvice.slice(0,4)"
            :key="index"
            :advice-content="advice"
            :number-per-row="4"
        />
    </div>

    <div
        v-else-if="partnerState === 'PENDING' || partnerState === 'VALIDATING'"
        class="px-huge"
    >
        <CardLoading
            :number-per-row="4"
            :number-of-rows="1"
        />
    </div>
</template>
