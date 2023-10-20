<script setup>
import useSWRV from "swrv";

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import EducatorHero from "@/js/components/advice/EducatorHero.vue";
import PartnerHero from "@/js/components/advice/PartnerHero.vue";
import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import { swrvOptions } from "@/js/constants/swrvConstants";
import { axiosFetcher} from "@/js/helpers/fetcher";
import useSwrvState from '@/js/helpers/useSwrvState';
import {useUserStore} from "@/js/stores/useUserStore";

import AdviceHero from '../components/advice/AdviceHero.vue'
import CardLoading from '../components/card/CardLoading.vue';
const userStore = useUserStore()
/**
 * Get the DAG Advice articles
 * and states
 */
const { data: dagAdvice, error: dagError, isValidating: dagValidating } = useSWRV(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS_BY_TYPE_DAG, axiosFetcher(userStore.getUserRequestParam), swrvOptions);
const { state: dagState, STATES: DAGSTATES } = useSwrvState(dagAdvice, dagError, dagValidating);
/**
 * Get the Partner adviceA
 * and states
 */
const { data: partnerAdvice, error: partnerError, isValidating: partnerValidating } = useSWRV(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS_BY_TYPE_PARTNER, axiosFetcher(userStore.getUserRequestParam), swrvOptions);
const { state: partnerState, STATES: PARTNERSTATES } = useSwrvState(partnerAdvice, partnerError, partnerValidating);

/**
 * Get General Advice articles (your work, classroom, learning)
 * and states
 */
const { data: generalAdvice, error: generalError, isValidating: generalValidating } = useSWRV(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS_BY_TYPE_YOUR, axiosFetcher(userStore.getUserRequestParam), swrvOptions);
const { state: generalState, STATES: GENERALSTATE } = useSwrvState(generalAdvice, generalError, generalValidating);


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
    <div class="grid grid-cols-1 gap-10 place-items-center mt-10 px-5 md:!grid-cols-2 lg:!grid-cols-3 lg:!px-huge">
        <template v-if="generalAdvice && generalAdvice.length">
            <AdviceCard
                v-for="advice in generalAdvice"
                :key="advice.guid"
                :data="advice"
                :show-icon="true"
            />
        </template>
        <template v-else>
            <div
                class="col-span-1 md:!col-span-2 lg:!col-span-3"
            >
                <CardLoading
                    :number-of-rows="1"
                    :number-per-row="3"
                />
            </div>
        </template>
    </div>

    
    <PartnerHero />
    <CarouselGenerator
        :show-count="3"
        data-type="advice"
        :data-array="partnerAdvice ? partnerAdvice : []"
    />
</template>
