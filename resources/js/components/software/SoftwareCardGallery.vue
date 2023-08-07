<script setup>
import {computed, onBeforeUnmount, onMounted} from 'vue';
import {serverURL} from "@/js/constants/serverUrl";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import {axiosFetcher} from "@/js/helpers/fetcher";
import useSWRV from "swrv";
import useSwrvState from "@/js/helpers/useSwrvState";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {useUserStore} from '@/js/stores/useUserStore';
import {storeToRefs} from 'pinia';

import CardLoading from "@/js/components/card/CardLoading.vue";
import {useWindowStore} from "@/js/stores/useWindowStore";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {useRouter} from "vue-router";

const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore);

const {
    data: softwaresData,
    error: softwaresError,
    isValidating: softwaresIsValidating
} = useSWRV(() => currentUser.value.id ? `${serverURL}/fetchSoftwarePosts` : null, axiosFetcher, swrvOptions);

// const {state: eventsState, STATES: ALLSTATES} = useSwrvState(eventsData, eventsError, eventsIsValidating)
const {state: softwaresState, STATES: ALLSTATES} = useSwrvState(softwaresData, softwaresError, softwaresIsValidating);

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
const router= useRouter()
const windowStore = useWindowStore()
const {isMobile, windowWidth} = storeToRefs(windowStore)



const responsiveDisplaySoftware = computed(() => {
    if(isMobile.value){
        return softwaresData.value.slice(0,4) || []
    }else{
        return softwaresData.value
    }
})

const handleClickSeeMore = () => {
    router.push('/browse/software')
}
</script>
<template>
    <template v-if="softwaresData">
        <div class="grid md:grid-cols-2 grid-cols-1 gap-6 place-items-center px-10 xl:!grid-cols-3 xl:!px-20">
            <SoftwareCard
                v-for="(software,index) in responsiveDisplaySoftware"
                :key="index"
                :software="software"
                :number-per-row="4"
            />
            <GenericButton
                v-show="isMobile"
                :callback="handleClickSeeMore"
                s
                class="
                    !bg-white
                    hover:!bg-main-darkTeal
                    !rounded-none
                    !text-main-darkTeal
                    hover:!text-white
                    border-2
                    border-main-darkTeal
                    place-self-center
                    md:!col-span-2
                    "
            >
                View all software
            </GenericButton>
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
