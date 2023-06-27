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
    <CardWrapper
        class="px-huge"
        :key="softwareLoading"
        :card-data="softwaresData ? softwaresData : []"
        :loading-state="softwareLoading"
        :row-count="3"
        :col-count="4"
        :section-type="'software'"
    />
    <!-- <template v-if="softwareList">
        <div class="flex flex-row flex-wrap w-full justify-between pt-10 gap-6 px-20">
            <SoftwareCard
                v-for="(software,index) in softwareList"
                :key="index"
                :software="software"
                :number-per-row="4"
            />
        </div>
    </template>
    <template v-else>
        Loading
    </template> -->
</template>
