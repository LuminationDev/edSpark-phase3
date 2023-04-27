<script setup>
    /**
     * Components
     */
    import DashboardHero from '../components/dashboard/DashboardHero.vue';
    import BlackOverlay from '../components/dashboard/BlackOverlay.vue';
    import FirstVisitForm from '../components/dashboard/FirstVisitForm.vue';
    import SectionHeader from '../components/global/SectionHeader.vue';

    /**
     * Depends on
     */
    import { ref, reactive, watch,  } from 'vue';
    import oktaAuth from '../constants/oktaAuth';

    /**
     * I guess I should pick up some ____ from the store on my way home
     * (import and set up stores)
     */
    import { useUserStore } from '../stores/useUserStore';

    const userStore = useUserStore();

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
            userStore.loadCurrentUser(emailCheck.userdata.user_id);
        } else {
            isFirstVisit.value = true;
        }
    };

    getIdToken();

</script>

<template>
    <div>
        <DashboardHero
            class="-mt-[140px]"
        />

        <BlackOverlay
            :isFirstVisit="isFirstVisit"
        />

        <div
            v-if="isFirstVisit"
            class="relative"
        >
            <FirstVisitForm
                :isFirstVisit="isFirstVisit"
                :userDetails="userDetails"
            />
        </div>

        <!-- Individual Sections -->

        <SectionHeader
            :classes="'bg-[#339999]'"
            :section="'events'"
            :title="'New Events'"
            :buttonText="'View all events'"
        />

        <!-- Events Cards Here -->

        <SectionHeader
            :classes="'bg-[#1C5CA9]'"
            :section="'software'"
            :title="'Top Software'"
            :buttonText="'View all software'"
        />

        <!-- Software Cards Here -->

        <SectionHeader
            :classes="'bg-[#0A7982]'"
            :section="'advice'"
            :title="'Advice'"
            :buttonText="'View all resources'"
        />

        <!-- Advice Cards Here -->

        <SectionHeader
            :classes="'bg-[#002858]'"
            :section="'schools'"
            :title="'Latest School Profiles'"
            :buttonText="'View all schools'"
        />
    </div>



</template>
