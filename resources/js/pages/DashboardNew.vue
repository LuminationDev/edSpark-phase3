<script setup>
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
import CardCarouselWrapper from '../components/card/CardCarouselWrapper.vue';
import CardWrapper from '../components/card/CardWrapper.vue';
import SoftwareRobot from '../components/svg/SoftwareRobot.vue';

/**
 * Card Components /sections
 */
import SoftwareDashboard from '../components/dashboard/SoftwareDashboard.vue';
import AdviceDashboard from '../components/dashboard/AdviceDashboard.vue';
import EventsDashboard from '../components/dashboard/EventsDashboard.vue';
import SchoolsDashboard from '../components/dashboard/SchoolsDashboard.vue';

/**
 * Loading Cards
 */
import CardLoading from '../components/card/CardLoading.vue';

/**
 * Depends on
 */
import {ref, reactive, computed, onMounted} from 'vue';
import oktaAuth from '../constants/oktaAuth';

/**
 * I guess I should pick up some ____ from the store on my way home
 * (import and set up stores)
 */
import {useUserStore} from '../stores/useUserStore';
import {useRouter} from "vue-router";
import {serverURL} from "@/js/constants/serverUrl";
import {axiosFetcher, axiosSchoolFetcher} from "@/js/helpers/fetcher";
import useSwrvState from "@/js/helpers/useSwrvState";
import useSWRV from "swrv";
import {storeToRefs} from "pinia";
import axios from 'axios';
import {swrvOptions} from "@/js/constants/swrvConstants";

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
    // try {
    //     idToken.value = await oktaAuth.tokenManager.get('idToken');
    //     claims.value = await idToken.value.claims;
    //     console.log('Below this is IDToken')
    //     console.log(idToken.value);

    //     // /**
    //     //  * Retireve id token from local storage
    //     //  * send it to laravel authentication api
    //     //  * bypass the laravel authentication
    //     //  *
    //     //  */
    //     // console.log('###############################');
    //     // let token = localStorage.getItem('okta-token-storage');
    //     // token = JSON.parse(token);
    //     // token = token.accessToken.accessToken;
    //     // console.log("TOKEN", token);

    //     // // check if user role requires admin access if yes do\fire this

    //     // await axios({
    //     //     method: 'POST',
    //     //     url: 'http://localhost:8000/api/authenticate',
    //     //     data: {
    //     //         accessToken: token
    //     //     },
    //     //     headers: {
    //     //         'Authentication': `Bearer ${token}`
    //     //     }
    //     // }).then(response => {
    //     //     console.log("RESPONSE", response);
    //     //     if (response.data.status === 'success') {
    //     //         localStorage.setItem('authenticatedToken', JSON.stringify(response.data));
    //     //         // window.open("http://localhost:8000/admin")
    //     //         // window.location.href = 'http://localhost:8000/admin/autologin';
    //     //     }
    //     // }).catch (err => {
    //     //     throw new error(err);
    //     // })
    //     // console.log('###############################');


    //     /**
    //      * User Details
    //      */
    //     userDetails.name = claims.value.name;
    //     userDetails.email = claims.value.email;
    //     userDetails.siteId = claims.value.mainsiteid;
    //     userDetails.roleId = claims.value.mainrolecode;

    //     await checkFirstVisit(claims.value.email);


    // } catch (e) {
    //     console.warn('Failed to get Auth data. User is not logged in')
    // }

    try {
        const response = await axios.get('http://localhost:8000/okta-data');
        // console.log("RESPONSE", response);
        if (response.data.success === true) {
            email.value = response.data.email;
            await checkFirstVisit(email.value);
        } else{
            currentUser.value.id = 9999
        }
    } catch (error) {
        console.error(error);
        console.warn('Failed to get Auth data. User is not logged in')
        currentUser.value.id = 9999
    }
};

/**
 * Check if user has an exisitng account
 */
const checkFirstVisit = async (emailAddress) => {
    console.log(emailAddress);
    let emailCheck = await userStore.checkUser(emailAddress);
    if (emailCheck.status === true) {
        isFirstVisit.value = false;
        await userStore.fetchCurrentUserAndLoadIntoStore(emailCheck.userdata.user_id);
    } else {
        isFirstVisit.value = true;
    }
}

getIdToken()


const {
    data: eventsData,
    error: eventsError,
    isValidating: eventsIsValidating
} = useSWRV(() => currentUser.value.id ? `${serverURL}/fetchEventPosts` : null, axiosFetcher, swrvOptions)
const {
    data: softwaresData,
    error: softwaresError,
    isValidating: softwaresIsValidating
} = useSWRV(() => currentUser.value.id ? `${serverURL}/fetchSoftwarePosts` : null, axiosFetcher, swrvOptions)
const {
    data: advicesData,
    error: advicesError,
    isValidating: advicesIsValidating
} = useSWRV(() => currentUser.value.id ? `${serverURL}/fetchAdvicePosts` : null, axiosFetcher, swrvOptions)
const {
    data: schoolsData,
    error: schoolsError,
    isValidating: schoolsIsValidating
} = useSWRV(() => currentUser.value.id ? `${serverURL}/fetchFeaturedSchools` : null, axiosSchoolFetcher, swrvOptions)

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

// const handleAuthentication = async () => {
//     // alert('Handle authentication function called');
//     /**
//      * Retireve id token from local storage
//      * send it to laravel authentication api
//      * bypass the laravel authentication
//      *
//      */
//     console.log('###############################');
//     let token = localStorage.getItem('okta-token-storage');
//     token = JSON.parse(token);
//     token = token.accessToken.accessToken;
//     console.log("TOKEN", token);

//     // check if user role requires admin access if yes do\fire this

//     await axios({
//         method: 'POST',
//         url: 'http://localhost:8000/api/authenticate',
//         data: {
//             accessToken: token,
//             id: 33
//         },
//         headers: {
//             'Authorization': `Bearer ${token}`
//         }
//     }).then(response => {
//         console.log("RESPONSE", response);
//         if (response.status === 302 || response.data.status === 'success') {
//             localStorage.setItem('authenticatedToken', JSON.stringify(response.data));
//             console.log('auth remote success');
//             // window.open("http://localhost:8000/admin",'_blank')
//             window.location.href = 'http://localhost:8000/admin/autologin';
//         }
//     }).catch(err => {
//         console.log(err.message)
//         throw new Error(err);
//     })
//     console.log('###############################');
// }

// console.log(router.currentRoute.value.query);
// const params = new URLSearchParams(router.currentRoute.value.query);
// console.log("PARAMS", params);
// const email = params.get('email');
// console.log('EMAIL ID:', email);
const email = ref(null);

onMounted(async () => {


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

        <!--        Individual Sections -->

        <SectionHeader
            :classes="'bg-primary-teal'"
            :section="'events'"
            :title="'New Events'"
            :button-text="'View all events'"
            :button-callback="() => router.push('/browse/events')"
        />

        <!-- Events Cards Here -->
        <CardCarouselWrapper
            :key="eventsLoading"
            :card-data="eventsData ? eventsData : []"
            :loading="eventsLoading"
            :row-count="1"
            :col-count="3"
            :section-type="'events'"
            :type-tag-color="'bg-secondary-red'"
        >
        </CardCarouselWrapper>

        <SectionHeader
            :classes="'bg-secondary-darkBlue'"
            :section="'software'"
            :title="'Top Software'"
            :button-text="'View all software'"
            :button-callback="() => router.push('/browse/softwares')"
        />

        <!-- Software Cards Here -->
        <div class="py-8 px-huge flex flex-row gap-[24px] relative group/bg">
            <div
                class="absolute softwareRobot -z-10 transition-all duration-700 opacity-10 top-1/2 -translate-y-1/2 left-1/3 group-hover/bg:left-[15%] group-hover/bg:scale-125"
            >
                <SoftwareRobot />
            </div>
            <div class="w-[35%] flex place-items-center pl-[29px]">
                <div class="w-full h-[700px] grid grid-cols-6 grid-rows-2">
                    <div class="col-span-1 row-span-1">
                        <img
                            class=""
                            src="../../assets/images/departmentProvidedAndApproved.png"
                            alt="Department Approved And Provided"
                        >
                    </div>
                    <div class="col-span-5 row-span-1 grid grid-rows-3 h-full">
                        <div class="row-span-1 px-8">
                            <h5 class="text-[21px] font-semibold pt-4">
                                Department Provided
                            </h5>
                            <p class="w-9/12">
                                These applications are provided by the department
                                at no cost to schools
                            </p>
                        </div>
                        <div class="row-span-1" />
                        <div class="row-span-1 flex flex-col h-full px-8">
                            <h5 class="text-[21px] font-semibold mt-auto">
                                Department Approved
                            </h5>
                            <p class="pb-4 w-9/12">
                                These applications have been risk assessed and can
                                be safely used in schools
                            </p>
                        </div>
                    </div>
                    <div class="col-span-2 row-span-1 flex">
                        <img
                            class="h-full pt-12 pb-8 ml-auto"
                            src="../../assets/images/negotiatedDeals.png"
                            alt="Negotiated Deals"
                        >
                    </div>
                    <div class="col-span-3 row-span-1 flex flex-col pl-8">
                        <h5 class="text-[18px] font-semibold mt-auto">
                            Negotiated Deals
                        </h5>
                        <p class="pb-4 w-[85%]">
                            Still risk assessed, these applications have an agreement
                            in place for schools to receive better value. Schools will
                            need to fund purchases
                        </p>
                    </div>
                </div>
            </div>
            <CardWrapper
                class="w-[65%]"
                :key="softwareLoading"
                :card-data="softwaresData ? softwaresData : []"
                :loading="softwareLoading"
                :row-count="2"
                :col-count="2"
                :section-type="'software'"
                :has-info-section="true"
                :additional-classes="'!w-[372px]'"
            />
        </div>

        <!-- <SoftwareDashboard
            :softwares="softwaresData ? softwaresData : []"
            :software-loading="softwareLoading"
        /> -->
        <!-- <CardLoading class="px-huge" :number-per-row="2" :additional-classes="'!justify-end'" /> -->

        <!-- UNIFORMITY & KEEPING ALL OF THE STUF THAT ISNT CARDS -->
        <!-- <SoftwareDashboard v-if="!softwareLoading" :softwares="softwaresData" :software-loading="softwareLoading" />
        <CardLoading v-else class="px-huge" :number-per-row="2" :additional-classes="'!justify-end'" /> -->

        <SectionHeader
            :classes="'bg-primary-darkTeal'"
            :section="'advice'"
            :title="'Advice'"
            :button-text="'View all resources'"
            :button-callback="() => router.push('/browse/advices')"
        />

        <CardCarouselWrapper
            :key="adviceLoading"
            :card-data="advicesData ? advicesData : []"
            :loading="adviceLoading"
            :row-count="1"
            :col-count="2"
            :additional-classes="'w-[66.66%]'"
            :section-type="'advice'"
            :advice-type="'Dashboard'"
            :loading-classes="'w-[66.66%]'"
        >
            <template #cardInfoSection>
                <div class="grid w-[33.33%] gap-[24px] h-full px-[29px]">
                    <div class="col-span-1">
                        <div class="grid grid-cols-3 row-span-4 py-4">
                            <div class="col-span-1 row-span-1">
                                <img
                                    class=""
                                    src="../../assets/images/WhatIsDag.png"
                                    alt="Digital Adoption Group Icon"
                                >
                            </div>
                            <div class="col-span-2 row-span-1 flex place-items-center">
                                <h4 class="text-[24px] font-semibold">
                                    What is the D.A.G?
                                </h4>
                            </div>
                            <div class="col-span-3 row-span-3 py-8">
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
            </template>
        </CardCarouselWrapper>

        <!-- Advice Cards Here -->
        <!-- <AdviceDashboard v-if="!adviceLoading" :advice="advicesData" />
        <CardLoading v-else class="px-huge" :number-per-row="2" :additional-classes="'!justify-end'" /> -->

        <SectionHeader
            :classes="'bg-primary-navy'"
            :section="'schools'"
            :title="'Latest School Profiles'"
            :button-text="'View all schools'"
            :button-callback="() => router.push('/browse/schools')"
        />


        <CardCarouselWrapper
            :key="schoolsLoading"
            :card-data="schoolsData ? schoolsData : []"
            :loading="schoolsLoading"
            :row-count="1"
            :col-count="4"
            :section-type="'schools'"
        />

        <!-- School Cards Here -->
        <!-- <SchoolsDashboard v-if="!schoolsLoading" :schools="schoolsData" />
        <CardLoading v-else class="px-huge" :number-per-row="4" /> -->
    </div>
</template>
