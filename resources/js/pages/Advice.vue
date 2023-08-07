<script setup>
import AdviceHero from '../components/advice/AdviceHero.vue'
import useSWRV from "swrv";
import useSwrvState from '@/js/helpers/useSwrvState';
import EducatorHero from "@/js/components/advice/EducatorHero.vue";
import PartnerHero from "@/js/components/advice/PartnerHero.vue";
import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import { serverURL } from "@/js/constants/serverUrl";
import { axiosFetcher } from "@/js/helpers/fetcher";
import { useRouter } from "vue-router";
import { swrvOptions } from "@/js/constants/swrvConstants";
import CardLoading from '../components/card/CardLoading.vue';

import CardCarouselWrapper from '../components/card/CardCarouselWrapper.vue';
import CardWrapper from '../components/card/CardWrapper.vue';
import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";

const router = useRouter();

/**
 * Get the DAG Advice articles
 * and states
 */
const { data: dagAdvice, error: dagError, isValidating: dagValidating } = useSWRV(`${serverURL}/fetchAdvicePostByType/DAG advice`, axiosFetcher, swrvOptions);
const { state: dagState, STATES: DAGSTATES } = useSwrvState(dagAdvice, dagError, dagValidating);
/**
 * Get the Partner advice
 * and states
 */
const { data: partnerAdvice, error: partnerError, isValidating: partnerValidating } = useSWRV(`${serverURL}/fetchAdvicePostByType/Partner`, axiosFetcher, swrvOptions);
const { state: partnerState, STATES: PARTNERSTATES } = useSwrvState(partnerAdvice, partnerError, partnerValidating);

/**
 * Get General Advice articles (your work, classroom, learning)
 * and states
 */
const { data: generalAdvice, error: generalError, isValidating: generalValidating } = useSWRV(`${serverURL}/fetchAdvicePostByType/${['Your Classroom', 'Your Work', 'Your Learning']}`, axiosFetcher, swrvOptions);
const { state: generalState, STATES: GENERALSTATE } = useSwrvState(generalAdvice, generalError, generalValidating);

const handleBrowseAllAdvice = () => {
    router.push('/browse/advice')
}

</script>

<template>
    <AdviceHero />
    <div class="AdviceContentContainer DAGAdviceRow flex flex-col h-full">
        <CarouselGenerator
            :show-count="3"
            data-type="advice"
            :data-array="dagAdvice ? dagAdvice : []"
        />
    </div>
    <EducatorHero />
    <div class="mt-14 px-5 lg:!px-huge">
        <CardWrapper
            :key="generalState"
            :card-data="generalAdvice ? generalAdvice : []"
            :loading-state="generalState"
            :row-count="2"
            :col-count="3"
            :section-type="'advice'"
            :advice-type="'General'"
        />
    </div>

    <PartnerHero />

    <CarouselGenerator
        :show-count="3"
        data-type="advice"
        :data-array="partnerAdvice ? partnerAdvice : []"
    />
</template>
