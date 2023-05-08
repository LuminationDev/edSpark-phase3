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
 * Depends on
 */
import {ref, reactive, watch,} from 'vue';
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

const userStore = useUserStore();
const eventStore = useEventsStore();
const softwareStore = useSoftwareStore();
const adviceStore = useAdviceStore();
const schoolsStore = useSchoolsStore();

/**
 * SVG's
 */

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
 */
const getIdToken = async () => {
    idToken.value = await oktaAuth.tokenManager.get('idToken');
    claims.value = await idToken.value.claims;

    /**
     * User Details
     */
    userDetails.name = claims.value.name;
    userDetails.email = claims.value.email;
    userDetails.siteId = claims.value.mainsiteid;
    userDetails.roleId = claims.value.mainrolecode;

    checkFirstVisit(claims.value.email);
};

/**
 * Check if user has an exisitng account
 */
const checkFirstVisit = async (emailAddress) => {
    let emailCheck = await userStore.checkUser(emailAddress);
    if (emailCheck.status === true) {
        isFirstVisit.value = false;
        await userStore.loadCurrentUser(emailCheck.userdata.user_id);
    } else {
        isFirstVisit.value = true;
    }
};

getIdToken();

/**
 * Data for the cards (hopefully it'll plug straight in)
 * Events
 * Software
 * Advice
 * Schools
 */
const events = ref([]);
const softwares = ref([]);
const advice = ref([]);
const schools = ref([]);

const router = useRouter()

const loadDashboardData = async () => {
    events.value = await eventStore.loadEvents();
    softwares.value = await softwareStore.loadArticles();
    advice.value = await adviceStore.loadDashboardResources();
    schools.value = await schoolsStore.loadSchools();


    console.log(typeof schools.value);
};

loadDashboardData();

</script>

<template>
    <div>
        <DashboardHero
            class="-mt-[140px]"
        />

        <BlackOverlay
            :is-first-visit="isFirstVisit"
        />

        <div
            v-if="isFirstVisit"
            class="relative"
        >
            <FirstVisitForm
                :is-first-visit="isFirstVisit"
                :user-details="userDetails"
            />
        </div>

        <!--        Individual Sections -->

        <SectionHeader
            :classes="'bg-[#339999]'"
            :section="'events'"
            :title="'New Events'"
            :button-text="'View all events'"
        />

        <!--        Events Cards Here-->
        <!--        <EventsDashboard-->
        <!--            :events="events"-->
        <!--        />-->

        <SectionHeader
            :classes="'bg-[#1C5CA9]'"
            :section="'software'"
            :title="'Top Software'"
            :button-text="'View all software'"
            :button-callback="() => router.push('/browse/software')"
        />

        <!-- Software Cards Here -->
        <SoftwareDashboard
            :softwares="softwares"
        />

        <SectionHeader
            :classes="'bg-[#0A7982]'"
            :section="'advice'"
            :title="'Advice'"
            :button-text="'View all resources'"
            :button-callback="() => router.push('/browse/advice')"
        />

        <!-- Advice Cards Here -->
        <AdviceDashboard
            :advice="advice"
        />

        <SectionHeader
            :classes="'bg-[#002858]'"
            :section="'schools'"
            :title="'Latest School Profiles'"
            :button-text="'View all schools'"
            :button-callback="() => router.push('/browse/schools')"
        />

        <!-- School Cards Here -->
        <SchoolsDashboard
            :schools="schools"
        />
    </div>
</template>
