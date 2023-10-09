<script setup>
import axios from 'axios';
import {storeToRefs} from "pinia";
import useSWRV from "swrv";
import {computed, onMounted, onUnmounted, reactive, ref} from 'vue';
import {useRouter} from "vue-router";

import CardLoading from '@/js/components/card/CardLoading.vue';
import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";
import BlackOverlay from '@/js/components/dashboard/BlackOverlay.vue';
import DashboardHero from '@/js/components/dashboard/DashboardHero.vue';
import FirstVisitForm from '@/js/components/dashboard/FirstVisitForm.vue';
import SectionHeader from '@/js/components/global/SectionHeader.vue';
import SchoolSectionDashboard from "@/js/components/schools/SchoolSectionDashboard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import DepartmentApprovedSolo from '@/js/components/svg/DepartmentApprovedSolo.vue';
import DeptApprovedIcon from "@/js/components/svg/software/DeptApprovedIcon.vue";
import DeptProvidedIcon from "@/js/components/svg/software/DeptProvidedIcon.vue";
import SoftwareRobot from '@/js/components/svg/SoftwareRobot.vue';
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {appURL} from "@/js/constants/serverUrl";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {getDistanceBetweenElements} from "@/js/helpers/drawingHelpers";
import {axiosFetcherParams} from "@/js/helpers/fetcher";
import {useSchoolsStore} from "@/js/stores/useSchoolsStore";
import {useUserStore} from '@/js/stores/useUserStore';
import {useWindowStore} from "@/js/stores/useWindowStore";

const router = useRouter()
const userStore = useUserStore();
const {currentUser} = storeToRefs(userStore)
const {isMobile} = storeToRefs(useWindowStore())


const idToken = ref('');
const claims = ref({});
const isFirstVisit = ref(false);
const email = ref(null);

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
const getIdToken = (async () => {
    try {
        const response = await axios.get(`${appURL}/okta-data`);
        if (response.data.success) {
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
    }
})();

/**
 * Check if user has an exisitng account
 */
const checkFirstVisit = async (emailAddress) => {
    console.log(emailAddress);
    const emailCheck = await userStore.checkUser(emailAddress);
    if (emailCheck.isFirstTimeVisit === false) {
        isFirstVisit.value = false;
        await userStore.fetchCurrentUserAndLoadIntoStore(emailCheck.userdata.user_id);
    } else {
        isFirstVisit.value = true;
    }
}


const shouldStartSwrv = computed(() => {
    return Boolean(currentUser.value.id)
})

const {
    data: eventsData,
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.EVENT.FETCH_EVENT_POSTS : null, axiosFetcherParams(userStore.getUserRequestParam), swrvOptions)
const {
    data: softwaresData,
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.SOFTWARE.FETCH_SOFTWARE_POSTS : null, axiosFetcherParams(userStore.getUserRequestParam), swrvOptions)
const {
    data: advicesData,
} = useSWRV(() => shouldStartSwrv.value ? API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS : null, axiosFetcherParams(userStore.getUserRequestParam), swrvOptions)


const onCloseFirstVisitPopup = () => {
    isFirstVisit.value = false;
};


/**
 * Handling line length on the software dashboard highlights
 */
const top = ref('');
const distanceBetweenEls = ref('');
const floatingLineClasses = ref('');


const adjustConnectingLinePositions = () => {
    const listContainers = document.querySelectorAll('.softwareDashboardContentContainer');

    if (listContainers.length === 0) return; // Exit early if no containers found

    const [firstContainer] = listContainers;
    const lastContainer = listContainers[listContainers.length - 1];

    distanceBetweenEls.value = getDistanceBetweenElements(
        firstContainer,
        lastContainer
    );

    const firstElHeight = firstContainer.offsetHeight;
    top.value = firstContainer.offsetTop + firstElHeight / 2;
    floatingLineClasses.value = `top-[${top.value}] h-[${distanceBetweenEls.value}px]`;
};

const handleResize = () => adjustConnectingLinePositions()

onMounted(async () => {
    window.addEventListener('resize', handleResize);
    handleResize();
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});


const softwareResponsiveData = computed(() => {
    if (!softwaresData.value.length) {
        return []
    } else if (isMobile.value) {
        return softwaresData.value.slice(0, 2)
    } else {
        return softwaresData.value.slice(0, 4)
    }
})

</script>

<template>
    <div>
        <DashboardHero class="-mt-extraHuge" />
        <template v-if="isFirstVisit">
            <BlackOverlay :is-first-visit="isFirstVisit" />

            <div class="relative">
                <FirstVisitForm
                    :user-details="userDetails"
                    @on-close-popup="onCloseFirstVisitPopup"
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
        <div class="flex flex-col gap-6 group/bg h-full py-8 relative lg:!flex-row lg:!px-huge">
            <div
                class="
                    -translate-y-1/2
                    md:!block
                    -z-10
                    absolute
                    top-1/2
                    left-1/4
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
                <div class="grid grid-cols-1 gap-10 place-items-center px-8 lg:!grid-cols-2 xl:!px-20">
                    <SoftwareCard
                        v-for="software in softwareResponsiveData"
                        :key="software.guid"
                        :data="software"
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
                        <div class="col-span-2 flex ml-2 place-items-center row-span-1">
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
        <SchoolSectionDashboard />
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
