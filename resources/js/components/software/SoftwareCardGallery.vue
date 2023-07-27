<script setup>
import axios from 'axios';
import { computed } from 'vue';
import { serverURL } from "@/js/constants/serverUrl";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import { axiosFetcher } from "@/js/helpers/fetcher";
import useSWRV from "swrv";
import useSwrvState from "@/js/helpers/useSwrvState";
import { swrvOptions } from "@/js/constants/swrvConstants";
import { useUserStore } from '../../stores/useUserStore';
import { storeToRefs } from 'pinia';

import CardWrapper from '../card/CardWrapper.vue';
import CardLoading from "@/js/components/card/CardLoading.vue";

const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore);

const {
    data: softwaresData,
    error: softwaresError,
    isValidating: softwaresIsValidating
} = useSWRV(() => currentUser.value.id ? `${serverURL}/fetchSoftwarePosts` : null, axiosFetcher, swrvOptions);

// const {state: eventsState, STATES: ALLSTATES} = useSwrvState(eventsData, eventsError, eventsIsValidating)
const { state: softwaresState, STATES: ALLSTATES } = useSwrvState(softwaresData, softwaresError, softwaresIsValidating);

const softwareLoading = computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(softwaresState.value)) {
        console.log('door number one');
        return false;
    } else if ([ALLSTATES.PENDING].includes(softwaresState.value)) {
        console.log('door number two');
        return true;
    } else if ([ALLSTATES.VALIDATING].includes(softwaresState.value)) {
        console.log('door number three');
        return !softwaresData.value.length;
    } else {
        console.log('door number four');
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(softwaresState.value);
    }
});
/**
 * not needed once the fetch is done on the dashbaord. maybe have a seperate update or check here for each type
 * @type {{user_id: number, post_type: string}}
 */


</script>
<template>
    <template v-if="softwaresData">
        <div class="grid grid-cols-3 place-items-center gap-6 px-20">
            <SoftwareCard
                v-for="(software,index) in softwaresData"
                :key="index"
                :software="software"
                :number-per-row="4"
            />
        </div>
    </template>
    <template v-else>
        <div class="px-20">
            <CardLoading
                :number-of-rows="3"
                :number-per-row="3"
            />
        </div>
    </template>
</template>
