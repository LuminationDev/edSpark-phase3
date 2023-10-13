<script setup>

/**
 * NOT BEING USED ATM
 */
import {storeToRefs} from "pinia";
import useSWRV from "swrv"; 
import {computed, onBeforeMount, ref} from 'vue'
import {useRouter} from "vue-router";

import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";
import SectionHeader from "@/js/components/global/SectionHeader.vue";
import CreateSchoolForm from "@/js/components/schools/createSchool/CreateSchoolForm.vue";
import SchoolWelcomePopup from "@/js/components/schools/schoolPopup/SchoolWelcomePopup.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosSchoolFetcherParams} from "@/js/helpers/fetcher";
import useSwrvState from "@/js/helpers/useSwrvState";
import {useSchoolsStore} from "@/js/stores/useSchoolsStore";
import {useUserStore} from "@/js/stores/useUserStore";

import SchoolsSearchableMap from '../components/schools/schoolMap/SchoolsSearchableMap.vue';
import SchoolsHero from '../components/schools/SchoolsHero.vue';
import Loader from '../components/spinner/Loader.vue';

const createSchool = ref(false)
const showWelcomePopup = ref(false)
const router = useRouter();
const userStore = useUserStore()
const schoolStore = useSchoolsStore()
const {schools} = storeToRefs(schoolStore)
const {currentUser} = storeToRefs(userStore)


const {
    data: featuredSites,
    error: featuredSitesError,
    isValidating: isValidatingFeatured
} = useSWRV(API_ENDPOINTS.SCHOOL.FETCH_FEATURED_SCHOOL, axiosSchoolFetcherParams(userStore.getUserRequestParam), swrvOptions)

const {state, STATES} = useSwrvState(featuredSites, featuredSitesError, isValidatingFeatured)

const cardsLoading = computed(() => {
    if ([STATES.ERROR, STATES.STALE_IF_ERROR].includes(state.value)) {
        return false
    } else if ([STATES.PENDING].includes(state.value)) {
        return true
    } else if ([STATES.VALIDATING].includes(state.value)) {
        return !featuredSites.value.length;
    } else {
        return ![STATES.SUCCESS, STATES.STALE_IF_ERROR].includes(state.value)
    }
})

const schoolsAvailable = computed(() => {
    return Boolean(schools.value.length)
})


onBeforeMount(async () => {
    await schoolStore.loadSchools()

    /**
     * Perform check for user meta here
     * has_school field
     */
    let currentUserHasSchool
    const currentUserId = currentUser.value.id
    const currentUserRole = currentUser.value.role
    try {
        // await axios.post(`${serverURL}/getUserMetadata`,{id: 1, userMetakey: 'has_school'}).then(res => {
        //     console.log(res.data[0])
        //     currentUserHasSchool = res.data[0]['user_meta_value'] === 'false'? false : true
        //     // console.log('current user has_school meta is ' + currentUserHasSchool)
        // });

        if (currentUser.value && currentUser.value.metadata && Object.keys(currentUser.value.metadata) === 'has_school') {
            currentUserHasSchool = true;
        }

        if (!currentUserHasSchool && (currentUserRole === 'Principal' || currentUserRole === 'Superadmin')) {
            // console.log('School is not init yet. you should init the school')
            createSchool.value = false
        } else if (!currentUserHasSchool) {
            // console.log('Please notify your principal to set up the school')
        } else {
            // console.log('hasnt been handled yet')
        }

    } catch (err) {
        console.log(err)
    }
})

const handleFinishCreateSchool = () => {
    createSchool.value = false
}

const handleBrowseAllSchool = () => {
    router.push('/browse/school')
}

const handleCloseWelcomePopup = () => {
    showWelcomePopup.value = false
}

const handleSaveWelcomePopup = (data) => {
    console.log('Received from modal')
    console.log(data)
    showWelcomePopup.value = false

}
</script>
<template>
    <div
        v-if="createSchool"
        class="mt-10"
    >
        <CreateSchoolForm @finish-create-school="handleFinishCreateSchool" />
    </div>
    <div v-else>
        <SchoolWelcomePopup
            v-if="showWelcomePopup"
            class="mt-10"
            :show-popup="showWelcomePopup"
            :close-popup="handleCloseWelcomePopup"
            @send-click-outside-popup="handleCloseWelcomePopup"
            @send-save-popup="handleSaveWelcomePopup"
        />
        <SchoolsHero />
        <div class="featuredClassContainer py-5 lg:!py-20">
            <SectionHeader
                :classes="'bg-[#002858]'"
                :section="'schools'"
                :title="'Featured Schools'"
                :button-text="'View all schools'"
                :button-callback="handleBrowseAllSchool"
            />
            <CarouselGenerator
                :show-count="3"
                data-type="school"
                :data-array="featuredSites ? featuredSites : []"
            />
        </div>


        <div
            v-if="schoolsAvailable"
            class="px-5 py-5 xl:!px-20 xl:!py-20"
        >
            <SchoolsSearchableMap
                :key="schoolsAvailable"
                :schools="schools"
                :schools-available="schoolsAvailable"
            />
        </div>

        <div
            v-else
            class="flex w-full"
        >
            <Loader
                :loader-color="'#0072DA'"
                :loader-message="'Map loading'"
            />
        </div>
    </div>
</template>
