<script setup>
import {computed, onBeforeMount, onMounted, ref, watch} from 'vue'
import axios from 'axios'
import {useRouter} from "vue-router";
import {storeToRefs} from "pinia";
import useSWRV from "swrv";


import {schoolContentArrParser} from "@/js/helpers/jsonHelpers";
import {useUserStore} from "@/js/stores/useUserStore";
import {serverURL} from "@/js/constants/serverUrl";

import SchoolsHero from '../components/schools/SchoolsHero.vue';
import SearchableMap from '../components/schools/schoolMap/SearchableMap.vue';
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import CreateSchoolForm from "@/js/components/schools/createSchool/CreateSchoolForm.vue";
import SchoolWelcomePopup from "@/js/components/schools/schoolPopup/SchoolWelcomePopup.vue";
import SectionHeader from "@/js/components/global/SectionHeader.vue";
import SchoolsSearchableMap from '../components/schools/schoolMap/SchoolsSearchableMap.vue';
import Loader from '../components/spinner/Loader.vue';
import CardLoading from '../components/card/CardLoading.vue';

// const featuredSitesData = ref([])
const createSchool = ref(false)
const showWelcomePopup = ref(false)

const router = useRouter();

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const axiosFetcher = (url) => {
    return axios.get(url).then(res => {return res.data})
}


const {data: featuredSites, error: schoolsError} = useSWRV(`${serverURL}/fetchFeaturedSchools`, axiosFetcher)

const cardsLoading = ref(true);

const allSchools = ref([]);
const schoolsAvailable = ref(false);

const fetchAllSchools =  () => {
    axios.get(`${serverURL}/fetchAllSchools`).then(res =>{
        allSchools.value = res.data
        schoolsAvailable.value = true;
    })
};

fetchAllSchools();

const featuredSitesData = computed(() => {
    if(!featuredSites.value ) return []
    else{
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        cardsLoading.value = false;
        return schoolContentArrParser(featuredSites.value)
    }
});

console.log(featuredSitesData);

onBeforeMount(async () => {
    /**
     * Perform check for user meta here
     * has_school field
     */
    let currentUserHasSchool
    const currentUserId = currentUser.value.id
    const currentUserRole = currentUser.value.role
    try{
        console.log(currentUser.value.metadata);
        // await axios.post(`${serverURL}/getUserMetadata`,{id: 1, userMetakey: 'has_school'}).then(res => {
        //     console.log(res.data[0])
        //     currentUserHasSchool = res.data[0]['user_meta_value'] === 'false'? false : true
        //     // console.log('current user has_school meta is ' + currentUserHasSchool)
        // });

        if (Object.keys(currentUser.value.metadata) === 'has_school') {
            currentUserHasSchool = true;
        }

        if(!currentUserHasSchool && (currentUserRole == 'Principal' || currentUserRole == 'Superadmin')){
            // console.log('School is not init yet. you should init the school')
            createSchool.value = false
        } else if(!currentUserHasSchool){
            // console.log('Please notify your principal to set up the school')
        } else{
            // console.log('hasnt been handled yet')
        }

    } catch(err){
        console.log(err)
    }
})

const handleFinishCreateSchool = () =>{
    createSchool.value = false
}

const handleBrowseAllSchool = () => {
    router.push({ name: 'browse-schools' })
}

const handleCloseWelcomePopup = () => {
    showWelcomePopup.value = false
}

const handleSaveWelcomePopup = (data)=>{
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
        <div class=" py-20 ">
            <SectionHeader
                :classes="'bg-[#002858]'"
                :section="'schools'"
                :title="'Featured Schools'"
                :button-text="'View all schools'"
                :button-callback="handleBrowseAllSchool"
            />
            <div
                v-if="!cardsLoading"
                class="grid grid-cols-4 gap-[24px] w-full px-20 pt-8 "
            >
                <div
                    v-for="(school,index) in featuredSitesData.splice(0,4)"
                    :key="index"
                    class="col-span-1 bg-white cursor-pointer h-[470px] border-[0.5px]  border-black transition-all group hover:shadow-2xl"
                >
                    <SchoolCard
                        v-if="featuredSitesData"
                        :school-data="school"
                    />
                </div>
            </div>
            <div
                v-else
            >
                <CardLoading
                    :number-per-row="4"
                />
            </div>
        </div>


        <div
            v-if="schoolsAvailable"
            class="py-20 px-20"
        >
            <!-- <SearchableMap /> -->
            <SchoolsSearchableMap
                :key="schoolsAvailable"
                :schools="allSchools"
                :schools-available="schoolsAvailable"
            />
        </div>

        <div
            v-else
            class="w-full flex"
        >
            <Loader
                :loader-color="'#0072DA'"
                :loader-message="'Map Loading'"
            />
        </div>
    </div>
</template>
