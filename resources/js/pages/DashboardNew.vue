<script setup>
/**
 * Components
 */
import DashboardHero from '../components/dashboard/DashboardHero.vue';
import BlackOverlay from '../components/dashboard/BlackOverlay.vue';
import FirstVisitForm from '../components/dashboard/FirstVisitForm.vue';
import SectionHeader from '../components/global/SectionHeader.vue';

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
import {ref, reactive, computed} from 'vue';
import oktaAuth from '../constants/oktaAuth';

/**
 * I guess I should pick up some ____ from the store on my way home
 * (import and set up stores)
 */
import {useUserStore} from '../stores/useUserStore';
import {useEventsStore} from '../stores/useEventsStore';
import {useSoftwareStore} from '../stores/useSoftwareStore';
import {useAdviceStore} from '../stores/useAdviceStore';
import {useSchoolsStore} from '../stores/useSchoolsStore';
import {useRouter} from "vue-router";
import {serverURL} from "@/js/constants/serverUrl";
import {axiosFetcher, axiosSchoolFetcher} from "@/js/helpers/fetcher";
import useSwrvState from "@/js/helpers/useSwrvState";
import useSWRV from "swrv";
import {storeToRefs} from "pinia";

const router = useRouter()

const userStore = useUserStore();
const {currentUser } = storeToRefs(userStore)
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
        idToken.value = await oktaAuth.tokenManager.get('idToken');
        claims.value = await idToken.value.claims;
        console.log('Below this is IDToken')
        console.log(idToken.value);
        /**
         * User Details
         */
        userDetails.name = claims.value.name;
        userDetails.email = claims.value.email;
        userDetails.siteId = claims.value.mainsiteid;
        userDetails.roleId = claims.value.mainrolecode;

        await checkFirstVisit(claims.value.email);
    } catch(e){
        console.warn('Failed to get Auth data. User is not logged in')
    }
};

/**
 * Check if user has an exisitng account
 */
const checkFirstVisit = async (emailAddress) => {
    let emailCheck =  await userStore.checkUser(emailAddress);
    if (emailCheck.status === true) {
        isFirstVisit.value = false;
        await userStore.fetchCurrentUserAndLoadIntoStore(emailCheck.userdata.user_id);
    } else {
        isFirstVisit.value = true;
    }
}

getIdToken()
const swrvOptions = {
    revalidateOnFocus: false, // disable refresh on every focus, suspect its too often
    refreshInterval: 30000 // refresh or revalidate data every 30 secs
}

// more code but much fast
// had to refactor this as the normal implementation blocks the user identity fetching request :(
const { data: eventsData, error: eventsError, isValidating: eventsIsValidating } = useSWRV(() => currentUser.value.id ?`${serverURL}/fetchEventPosts`: null, axiosFetcher, swrvOptions)
const { data: softwaresData, error: softwaresError, isValidating: softwaresIsValidating } = useSWRV(() => currentUser.value.id ?`${serverURL}/fetchSoftwarePosts`: null, axiosFetcher, swrvOptions)
const { data: advicesData, error: advicesError, isValidating: advicesIsValidating } = useSWRV(() => currentUser.value.id ?`${serverURL}/fetchAdvicePosts`: null, axiosFetcher, swrvOptions)
const { data: schoolsData, error: schoolsError, isValidating: schoolsIsValidating } = useSWRV(() => currentUser.value.id ?`${serverURL}/fetchFeaturedSchools`: null, axiosSchoolFetcher, swrvOptions)

const {state: eventsState, STATES:ALLSTATES} = useSwrvState(eventsData,eventsError,eventsIsValidating)
const {state: softwaresState} = useSwrvState(softwaresData,softwaresError,softwaresIsValidating)
const {state: advicesState} = useSwrvState(advicesData,advicesError,advicesIsValidating)
const {state: schoolsState} = useSwrvState(schoolsData,schoolsError,schoolsIsValidating)

// who needs a one line ref to indicate loading state when you can have 10 lines 😆
const eventsLoading =  computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(eventsState.value)) {
        return false
    } else if ([ALLSTATES.PENDING].includes(eventsState.value)) {
        return true
    } else if ([ALLSTATES.VALIDATING].includes(eventsState.value)){
        return false
    } else {
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(eventsState.value)}
})
const softwareLoading =  computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(softwaresState.value)) {
        return false
    } else if ([ALLSTATES.PENDING].includes(softwaresState.value)) {
        return true
    } else if ([ALLSTATES.VALIDATING].includes(softwaresState.value)){
        return false
    } else {
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(softwaresState.value)}
})
const adviceLoading = computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(advicesState.value)) {
        return false
    } else if ([ALLSTATES.PENDING].includes(advicesState.value)) {
        return true
    } else if ([ALLSTATES.VALIDATING].includes(advicesState.value)){
        return false
    } else {
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(advicesState.value)}
})
const schoolsLoading =  computed(() => {
    if ([ALLSTATES.ERROR, ALLSTATES.STALE_IF_ERROR].includes(schoolsState.value)) {
        return false
    } else if ([ALLSTATES.PENDING].includes(schoolsState.value)) {
        return true
    } else if ([ALLSTATES.VALIDATING].includes(schoolsState.value)){
        return false
    } else {
        return ![ALLSTATES.SUCCESS, ALLSTATES.VALIDATING, ALLSTATES.STALE_IF_ERROR].includes(schoolsState.value)}
})

const onClosePopup = () => {
    isFirstVisit.value = false;
};

</script>

<template>
    <div>
        <DashboardHero
            class="-mt-extraHuge"
        />
        <template
            v-if="isFirstVisit"
        >
            <BlackOverlay
                :is-first-visit="isFirstVisit"
            />

            <div
                class="relative"
            >
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
            :button-callback="() => router.push('/browse/event')"
        />

        <!-- Events Cards Here -->
        <EventsDashboard
            v-if="!eventsLoading"
            :events="eventsData"
        />
        <CardLoading
            v-else
            class="px-huge"
            :number-per-row="3"
        />

        <SectionHeader
            :classes="'bg-secondary-darkBlue'"
            :section="'software'"
            :title="'Top Software'"
            :button-text="'View all software'"
            :button-callback="() => router.push('/browse/software')"
        />

        <!-- Software Cards Here -->
        <SoftwareDashboard
            v-if="!softwareLoading"
            :softwares="softwaresData"
        />
        <CardLoading
            v-else
            class="px-huge"
            :number-per-row="2"
            :additional-classes="'!justify-end'"
        />

        <SectionHeader
            :classes="'bg-primary-darkTeal'"
            :section="'advice'"
            :title="'Advice'"
            :button-text="'View all resources'"
            :button-callback="() => router.push('/browse/advice')"
        />

        <!-- Advice Cards Here -->
        <AdviceDashboard
            v-if="!adviceLoading"
            :advice="advicesData"
        />
        <CardLoading
            v-else
            class="px-huge"
            :number-per-row="2"
            :additional-classes="'!justify-end'"
        />

        <SectionHeader
            :classes="'bg-primary-navy'"
            :section="'schools'"
            :title="'Latest School Profiles'"
            :button-text="'View all schools'"
            :button-callback="() => router.push('/browse/schools')"
        />

        <!-- School Cards Here -->
        <SchoolsDashboard
            v-if="!schoolsLoading"
            :schools="schoolsData"
        />
        <CardLoading
            v-else
            class="px-huge"
            :number-per-row="4"
        />
    </div>
</template>
