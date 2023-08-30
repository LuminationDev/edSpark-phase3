<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

/**
 * Components
 */
import DashboardHero from '../components/dashboard/DashboardHero.vue';
import BlackOverlay from '../components/dashboard/BlackOverlay.vue';
import FirstVisitForm from '../components/dashboard/FirstVisitForm.vue';
import SectionHeader from '../components/global/SectionHeader.vue';

/**
 * Import Card wrapper
 */
import SoftwareRobot from '../components/svg/SoftwareRobot.vue';

import DeptApprovedIcon from "@/js/components/svg/software/DeptApprovedIcon.vue";
import DeptProvidedIcon from "@/js/components/svg/software/DeptProvidedIcon.vue";
import DepartmentApprovedSolo from '@/js/components/svg/DepartmentApprovedSolo.vue';
/**
 * Loading Cards
 */
import CardLoading from '@/js/components/card/CardLoading.vue';
import {ref, reactive, computed, onMounted} from 'vue';
import {useUserStore} from '../stores/useUserStore';
import {useRouter} from "vue-router";
import {appURL} from "@/js/constants/serverUrl";
import {axiosFetcherParams, axiosSchoolFetcher, axiosSchoolFetcherParams} from "@/js/helpers/fetcher";
import useSwrvState from "@/js/helpers/useSwrvState";
import useSWRV from "swrv";
import {storeToRefs} from "pinia";
import axios from 'axios';
import {swrvOptions} from "@/js/constants/swrvConstants";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";

const router = useRouter()
const userStore = useUserStore();
const {currentUser, isAdminAuthenticated} = storeToRefs(userStore)
/**
 * First things first. Handle the user details from okta
 */
const idToken = ref('');
const claims = ref({});
const isFirstVisit = ref(false);

const userDetails = reactive({
    name: '',
    email: '',
    siteId: '',
    roleId: ''
});

/**
 * Get the idToken from Okta and set up the claims
 * TODO: Push to the top of the app (where appropriate-considering in App.vue but the redirect from okta login might fail it)
 */
const getIdToken = async () => {

    try {
        const response = await axios.get(`${appURL}/okta-data`);
        // console.log("RESPONSE", response);
        if (response.data.success === true) {
            userDetails.name = response.data.name;
            userDetails.email = response.data.email;
            // userDetails.siteId = 106;
            // userDetails.roleId = 3;
            // userDetails.siteId = response.data.siteId; //TODO
            // userDetails.roleId = response.data.roleId; //TODO
            email.value = response.data.email;

            await checkFirstVisit(email.value);
        } else {
            // currentUser.value.id = 61
        }
    } catch (error) {
        console.error(error);
        console.warn('Failed to get Auth data. User is not logged in')
        // currentUser.value.id = 61
    }
};

/**
 * Check if user has an exisitng account
 */
const checkFirstVisit = async (emailAddress) => {
    console.log(emailAddress);
    let emailCheck = await userStore.checkUser(emailAddress);
    if (emailCheck.isFirstTimeVisit === false) {
        isFirstVisit.value = false;
        await userStore.fetchCurrentUserAndLoadIntoStore(emailCheck.userdata.user_id);
    } else {
        isFirstVisit.value = true;
    }

}

getIdToken()

const shouldStartSwrv = computed(() => {
    console.log(Boolean(currentUser.value.id))
    return Boolean(currentUser.value.id)
})

const {
    data: eventsData,
    error: eventsError,
    isValidating: eventsIsValidating
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS : null, axiosFetcherParams(userStore.getUserRequestParam), swrvOptions)
const {
    data: softwaresData,
    error: softwaresError,
    isValidating: softwaresIsValidating
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS : null, axiosFetcherParams(userStore.getUserRequestParam), swrvOptions)
const {
    data: advicesData,
    error: advicesError,
    isValidating: advicesIsValidating
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS : null, axiosFetcherParams(userStore.getUserRequestParam), swrvOptions)
const {
    data: schoolsData,
    error: schoolsError,
    isValidating: schoolsIsValidating
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.SCHOOL.FETCH_FEATURED_SCHOOL : null, axiosSchoolFetcherParams(userStore.getUserRequestParam), swrvOptions)

const {state: eventsState, STATES: ALLSTATES} = useSwrvState(eventsData, eventsError, eventsIsValidating)
const {state: softwaresState} = useSwrvState(softwaresData, softwaresError, softwaresIsValidating)
const {state: advicesState} = useSwrvState(advicesData, advicesError, advicesIsValidating)
const {state: schoolsState} = useSwrvState(schoolsData, schoolsError, schoolsIsValidating)

// who needs a one line ref to indicate loading state when you can have 10 lines 😆
// todo: compile all ref into an array and process with map
const eventsLoading = computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(eventsState.value)) {
        return false
    } else if ([ALLSTATES.PENDING].includes(eventsState.value)) {
        return true
    } else if ([ALLSTATES.VALIDATING].includes(eventsState.value)) {
        return false
    } else {
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(eventsState.value)
    }
})
const softwareLoading = computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(softwaresState.value)) {
        return false
    } else if ([ALLSTATES.PENDING].includes(softwaresState.value)) {
        return true
    } else if ([ALLSTATES.VALIDATING].includes(softwaresState.value)) {
        return false
    } else {
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(softwaresState.value)
    }
})
const adviceLoading = computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(advicesState.value)) {
        return false
    } else if ([ALLSTATES.PENDING].includes(advicesState.value)) {
        return true
    } else if ([ALLSTATES.VALIDATING].includes(advicesState.value)) {
        return false
    } else {
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(advicesState.value)
    }
})
const schoolsLoading = computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(schoolsState.value)) {
        return false
    } else if ([ALLSTATES.PENDING].includes(schoolsState.value)) {
        return true
    } else if ([ALLSTATES.VALIDATING].includes(schoolsState.value)) {
        return false
    } else {
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(schoolsState.value)
    }
})

const onClosePopup = () => {
    isFirstVisit.value = false;
};

const email = ref(null);


/**
 * Handling line length on the software dashboard highlights
 */
const top = ref('');
const distanceBetweenEls = ref('');
const floatingLineClasses = ref('');
const getConnectingLinePositions = () => {
    let listContainers = document.querySelectorAll('.softwareDashboardContentContainer');
    let firstContainer = listContainers[0];
    let lastContainer = listContainers[listContainers.length - 1];
    if(firstContainer && lastContainer) {

        distanceBetweenEls.value = getDistanceBetweenElements(
            firstContainer,
            lastContainer
        );

        let firstElHeight = firstContainer.offsetHeight;
        top.value = firstContainer.offsetTop + firstElHeight / 2;
        floatingLineClasses.value = `top-[${top.value}] h-[${distanceBetweenEls.value}px]`
    }
};

const getPositionAtCenter = (element) => {
    if (element) {
        const {top, left, width, height} = element.getBoundingClientRect();
        return {
            x: left + width / 2,
            y: top + height / 2
        };
    } else {
        return {
            x: 0,
            y: 0
        }
    }
}

const getDistanceBetweenElements = (a, b) => {
    const aPosition = getPositionAtCenter(a);
    const bPosition = getPositionAtCenter(b);

    return Math.hypot(aPosition.x - bPosition.x, aPosition.y - bPosition.y);
}

onMounted(async () => {
    window.addEventListener('resize', () => getConnectingLinePositions());
    getConnectingLinePositions();
});

</script>

<template>
    <div>
        <DashboardHero class="-mt-extraHuge" />
        <template v-if="isFirstVisit">
            <BlackOverlay :is-first-visit="isFirstVisit" />

            <div class="relative">
                <FirstVisitForm
                    :user-details="userDetails"
                    @on-close-popup="onClosePopup"
                />
            </div>
        </template>
        <SectionHeader
            :classes="'bg-main-teal'"
            :section="'events'"
            :title="'New Events'"
            :button-text="'View all events'"
            :button-callback="() => router.push('/browse/event')"
        />
        <CarouselGenerator
            data-type="events"
            :data-array="eventsData? eventsData : []"
        />

        <SectionHeader
            :classes="'bg-secondary-darkBlue'"
            :section="'software'"
            :title="'Top Software'"
            :button-text="'View all software'"
            :button-callback="() => router.push('/browse/software')"
        />

        <!-- Software Cards Here -->
        <div class="flex flex-col gap-[24px] group/bg h-full py-8 relative lg:!flex-row lg:!px-huge">
            <div
                class="
                    -translate-y-1/2
                    md:!block
                    -z-10
                    absolute
                    top-1/2
                    left-1/3
                    duration-700
                    group-hover/bg:left-[15%]
                    group-hover/bg:scale-125
                    hidden
                    opacity-10
                    softwareRobot
                    transition-all
                    "
            >
                <SoftwareRobot />
            </div>
            <div class="flex flex-col pl-8 place-items-center w-full lg:!w-[35%]">
                <div class="flex flex-row gap-4 h-1/2 w-full">
                    <div class="relative w-1/4">
                        <!--  first line-->
                        <div
                            class="absolute left-1/2 border-[2px] border-black connectingLine z-10"
                            :style="`height: ${distanceBetweenEls}px; top: ${top}px;`"
                        />
                        <div class="flex flex-col h-full relative z-20">
                            <div class="flex h-1/2 min-h-[200px] place-self-center">
                                <div
                                    class="
                                        bg-white
                                        border-[4px]
                                        border-black
                                        flex
                                        h-[100px]
                                        my-auto
                                        rounded-full
                                        w-[100px]
                                        "
                                >
                                    <DeptProvidedIcon class="h-full p-1" />
                                </div>
                            </div>
                            <div class="flex h-1/2 min-h-[200px] place-self-center">
                                <div
                                    class="
                                        bg-white
                                        border-[4px]
                                        border-black
                                        flex
                                        h-[100px]
                                        my-auto
                                        rounded-full
                                        w-[100px]
                                        "
                                >
                                    <DeptApprovedIcon class="h-full p-1" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="h-full ml-4 w-3/4">
                        <div class="flex flex-col h-full">
                            <div
                                class="
                                    flex
                                    justify-center
                                    flex-col
                                    h-1/2
                                    min-h-[200px]
                                    my-auto
                                    softwareDashboardContentContainer
                                    "
                            >
                                <h5 class="font-semibold pt-4 text-xl">
                                    Department Provided
                                </h5>
                                <p class="w-9/12">
                                    These applications are provided by the department
                                    at no cost to schools
                                </p>
                            </div>
                            <div
                                class="
                                    flex
                                    justify-center
                                    flex-col
                                    h-1/2
                                    min-h-[200px]
                                    my-auto
                                    softwareDashboardContentContainer
                                    "
                            >
                                <h5 class="font-semibold text-xl">
                                    Department Approved
                                </h5>
                                <p class="pb-4 w-9/12">
                                    These applications have been risk assessed and can
                                    be safely used in schools
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row gap-4 h-1/2 relative w-full">
                    <div class="relative w-1/4">
                        <div
                            class="absolute left-1/2 border-[1.5px] border-black border-dashed negotiatedDealsLine z-10"
                            :style="`height: ${distanceBetweenEls}px; top: calc(-${top}px + 60px);`"
                        />
                        <div class="flex flex-col h-full relative z-20">
                            <div class="flex h-3/4 place-self-center">
                                <div
                                    class="
                                        bg-white
                                        border-[2px]
                                        border-black
                                        border-dashed
                                        flex
                                        h-[100px]
                                        my-auto
                                        relative
                                        rounded-full
                                        w-[100px]
                                        "
                                >
                                    <DeptApprovedIcon class="h-full p-1" />
                                    <DepartmentApprovedSolo class="absolute -top-1 -right-1 h-[40px] w-[40px]" />
                                </div>
                            </div>
                            <div class="flex h-1/4 place-self-center" />
                        </div>
                    </div>

                    <div class="ml-4 w-3/4">
                        <div class="flex flex-col h-full">
                            <div class="flex justify-center flex-col h-3/4 min-h-[200px] my-auto">
                                <h5 class="font-semibold text-xl">
                                    Negotiated Deals
                                </h5>
                                <p class="pb-4 w-[85%]">
                                    Still risk assessed, these applications have an agreement
                                    in place for schools to receive better value. Schools will
                                    need to fund purchases
                                </p>
                            </div>
                            <div class="flex justify-center flex-col h-1/4 my-auto" />
                        </div>
                    </div>
                </div>
            </div>
            <template v-if="softwaresData">
                <div class="grid grid-cols-1 gap-6 place-items-center px-8 lg:!grid-cols-2 xl:!px-20">
                    <SoftwareCard
                        v-for="software in softwaresData.slice(0,4)"
                        :key="software.guid"
                        :data="software"
                        :number-per-row="2"
                    />
                </div>
            </template>
            <template v-else>
                <div class="px-8 w-full lg:!px-20">
                    <CardLoading
                        :number-of-rows="2"
                        :number-per-row="2"
                    />
                </div>
            </template>
        </div>

        <SectionHeader
            :classes="'bg-main-darkTeal'"
            :section="'advice'"
            :title="'Advice'"
            :button-text="'View all resources'"
            :button-callback="() => router.push('/browse/advice')"
        />
        <div class="flex flex-col w-full lg:!flex-row lg:!px-huge">
            <div class="DAGInfoSection w-full lg:!w-1/4">
                <div class="grid gap-6 h-full px-4 w-full">
                    <div class="grid grid-cols-3 py-4 row-span-4">
                        <div class="col-span-1 row-span-1">
                            <img
                                class=""
                                src="../../assets/images/WhatIsDag.png"
                                alt="Digital Adoption Group Icon"
                            >
                        </div>
                        <div class="col-span-2 flex place-items-center row-span-1">
                            <h4 class="font-semibold text-[24px]">
                                What is the DAG?
                            </h4>
                        </div>
                        <div class="col-span-3 py-8 row-span-3">
                            <p>
                                The Digital Adoption Group (DAG) is a cross-divisional
                                group that provides holistic and focused advice on
                                digital technologies
                            </p>
                            <p class="mt-8">
                                The objective of the DAG is to support leaders and
                                educators to integrate high-impact technologies by
                                providing system-wide and practical advice on what
                                technology to purchase for teaching and learning
                                and for what purpose.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="DAGAdviceCarousel w-full lg:!w-3/4">
                <CarouselGenerator
                    data-type="advice"
                    :data-array="advicesData ? advicesData : []"
                    special-attribute="twoThirdWide"
                />
            </div>
        </div>

        <SectionHeader
            :classes="'bg-main-navy'"
            :section="'schools'"
            :title="'Latest School Profiles'"
            :button-text="'View all schools'"
            :button-callback="() => router.push('/browse/school')"
        />

        <CarouselGenerator
            data-type="school"
            :data-array="schoolsData ? schoolsData : []"
        />
    </div>
</template>

<style scoped lang="scss">


.negotiatedDealsLine {
    width: 2px;
    background: linear-gradient(to top, transparent, #000);
    /* Fading effect to transparent */
}

.negotiatedDealsLine::before {
    content: "";
    position: absolute;

}
</style>
