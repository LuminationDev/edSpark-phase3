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
const router = useRouter()

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

    console.log(idToken);

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

const eventsLoading = ref(true);
const softwareLoading = ref(true);
const adviceLoading = ref(true);
const schoolsLoading = ref(true);

const loadDashboardData = async () => {
    eventStore.loadEvents().then(response => {
        events.value = response;
        eventsLoading.value = false;
    });

    softwareStore.loadArticles().then(response => {
        softwares.value = response;
        softwareLoading.value = false;
    });

    adviceStore.loadDashboardResources().then(response => {
        advice.value = response;
        adviceLoading.value = false;
    });

    schoolsStore.loadSchools().then(response => {
        schools.value = response;
        schoolsLoading.value = false;
    });
};

loadDashboardData();

const onClosePopup = () => {
    isFirstVisit.value = false;
};

</script>

<template>
    <div>
        <DashboardHero
            class="-mt-extraHuge"
        />

        <BlackOverlay
            v-if="isFirstVisit"
            :is-first-visit="isFirstVisit"
        />

        <div
            v-if="isFirstVisit"
            class="relative"
        >
            <FirstVisitForm
                :is-first-visit="isFirstVisit"
                :user-details="userDetails"
                @onClosePopup="onClosePopup"
            />
        </div>

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
            :events="events"
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
            :softwares="softwares"
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
            :advice="advice"
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
            :schools="schools"
        />
        <CardLoading
            v-else
            class="px-huge"
            :number-per-row="4"
        />
    </div>
</template>
