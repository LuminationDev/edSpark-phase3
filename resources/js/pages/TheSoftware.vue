<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {computed} from "vue";
import {useRouter} from "vue-router";
import {storeToRefs} from "pinia";
import useSWRV from "swrv";

import {axiosFetcher, axiosFetcherParams} from "@/js/helpers/fetcher";
import useSwrvState from "@/js/helpers/useSwrvState";
import {useUserStore} from "@/js/stores/useUserStore";
import {useWindowStore} from "@/js/stores/useWindowStore";

import {serverURL} from "@/js/constants/serverUrl";
import {swrvOptions} from "@/js/constants/swrvConstants";

import SectionHeader from "@/js/components/global/SectionHeader.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CardLoading from "@/js/components/card/CardLoading.vue";

import SoftwareHero from "@/js/components/software/SoftwareHero.vue";
import DeptNegotiatedIcon from "@/js/components/svg/software/DeptNegotiatedIcon.vue";
import DeptApprovedIcon from "@/js/components/svg/software/DeptApprovedIcon.vue";
import DeptProvidedIcon from "@/js/components/svg/software/DeptProvidedIcon.vue";

const router = useRouter()
const handleBrowseAllSoftware = () => {
    router.push('/browse/software')
}
const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore);

const windowStore = useWindowStore()
const {isMobile, windowWidth} = storeToRefs(windowStore)

const {
    data: softwaresData,
    error: softwaresError,
    isValidating: softwaresIsValidating
} = useSWRV(() => currentUser.value.id ? API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS : null, axiosFetcherParams(userStore.getUserRequestParam), swrvOptions);

const {state: softwaresState, STATES: ALLSTATES} = useSwrvState(softwaresData, softwaresError, softwaresIsValidating);

const softwareLoading = computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(softwaresState.value)) {
        return false;
    } else if ([ALLSTATES.PENDING].includes(softwaresState.value)) {
        return true;
    } else if ([ALLSTATES.VALIDATING].includes(softwaresState.value)) {
        return !softwaresData.value.length;
    } else {
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(softwaresState.value);
    }
});


const responsiveDisplaySoftware = computed(() => {
    if (isMobile.value) {
        return softwaresData.value.slice(0, 4) || []
    } else {
        return softwaresData.value
    }
})

const handleClickSeeMore = () => {
    router.push('/browse/software')
}
</script>

<template>
    <SoftwareHero />
    <div class="SoftwareContentContainer">
        <div class="HowItWorksContainer flex flex-col pt-4 px-5 lg:!pt-16 lg:!px-10 xl:!px-20">
            <div class="grid grid-cols-6 mt-6 lg:!flex lg:!flex-row lg:!justify-around">
                <div
                    class="
                        col-span-6
                        grid
                        gap-2
                        place-items-center
                        hover:cursor-pointer
                        md:!col-span-3
                        lg:!basis-1
                        lg:!flex
                        lg:!flex-col
                        lg:!gap-0
                        "
                    @click="router.push('/browse/software/provided')"
                >
                    <DeptProvidedIcon />
                    <div class="font-semibold mb-4 mt-10 text-center">
                        Department Provided
                    </div>
                    <div class="text-center">
                        These applications are provided by the department at no cost to schools
                    </div>
                </div>
                <div
                    class="
                        col-span-6
                        grid
                        place-items-center
                        hover:cursor-pointer
                        md:!col-span-3
                        lg:!basis-1
                        lg:!flex
                        lg:!flex-col
                        "
                    @click="router.push('/browse/software/approved')"
                >
                    <DeptApprovedIcon />
                    <div class="font-semibold mb-4 mt-10 text-center">
                        Department Approved
                    </div>
                    <div class="text-center">
                        These applications have been risk assessed and can be safely used in schools.
                    </div>
                </div>
                <div
                    class="col-span-6 grid place-items-center hover:cursor-pointer lg:!basis-1 lg:!flex lg:!flex-col"
                    @click="router.push('/browse/software/negotiated')"
                >
                    <DeptNegotiatedIcon />
                    <div class="font-semibold mb-4 mt-10 text-center">
                        Approved and Negotiatied
                    </div>
                    <div class="text-center">
                        Still risk assessed, these applications have an agreement in place for schools to receive better
                        value. Schools will need to fund the purchases
                    </div>
                </div>
            </div>
        </div>
        <div class="softwareListContainer">
            <SectionHeader
                :classes="'bg-[#002858]'"
                :section="'software'"
                title="Software"
                button-text="View all software"
                :button-callback="handleBrowseAllSoftware"
            />
        </div>
        <!--    Software Card Gallery    -->
        <div class="grid md:grid-cols-2 grid-cols-1 gap-6 place-items-center px-10 xl:!grid-cols-3 xl:!px-20">
            <template v-if="responsiveDisplaySoftware">
                <SoftwareCard
                    v-for="(software,index) in responsiveDisplaySoftware"
                    :key="index"
                    :software="software"
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
            </template>
            <template v-else>
                <div
                    class="col-span-1 md:!col-span-2 xl:!col-span-3"
                >
                    <CardLoading
                        :number-of-rows="1"
                        :number-per-row="3"
                    />
                </div>
            </template>
        </div>
    </div>
</template>
