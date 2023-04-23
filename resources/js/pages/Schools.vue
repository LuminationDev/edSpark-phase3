<script setup>
import {onBeforeMount, onMounted, ref} from 'vue'
import axios from 'axios'
import {useRouter} from "vue-router";
import {storeToRefs} from "pinia";

import {schoolContentArrParser} from "@/js/helpers/jsonHelpers";
import {useUserStore} from "@/js/stores/useUserStore";
import {serverURL} from "@/js/constants/serverUrl";

import SchoolsHero from '../components/schools/SchoolsHero.vue';
import SearchableMap from '../components/schools/schoolMap/SearchableMap.vue';
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import CreateSchoolForm from "@/js/components/schools/createSchool/CreateSchoolForm.vue";
import SchoolWelcomePopup from "@/js/components/schools/schoolPopup/SchoolWelcomePopup.vue";
import SectionHeader from "@/js/components/global/SectionHeader.vue";

const featuredSites = ref([])
const featuredSitesData = ref([])
const createSchool = ref(false)
const showWelcomePopup = ref(true)

const router = useRouter();

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

onBeforeMount(async () => {
    /**
     * Perform check for user meta here
     * has_school field
     */
    let currentUserHasSchool
    // const currentUserId = currentUser.value.id
    const currentUserId = 1
    const currentUserRole = currentUser.value.role
    try{
        await axios.post(`${serverURL}/getUserMetadata`,{id: 1, userMetakey: 'has_school'}).then(res => {
            console.log(res);
            currentUserHasSchool = res.data[0].user_meta_value == 'false'? false : true
            console.log('current user has_school meta is ' + currentUserHasSchool)
        })
        if(!currentUserHasSchool && (currentUserRole == 'Principal' || currentUserRole == 'Superadmin')){
            console.log('School is not init yet. you should init the school')
            createSchool.value = false
        } else if(!currentUserHasSchool){
            console.log('Please notify your principal to set up the school')
        } else{
            console.log('hasnt been handled yet')
        }

    } catch(err){
        console.log(err)
    }
})


onMounted(async () =>{
    // TODO switch route to featured schools
    await axios.get(`${serverURL}/fetchAllSchools`).then(res => {
        featuredSites.value = res.data.splice(0,4)
        featuredSitesData.value = schoolContentArrParser(featuredSites.value)
    })
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
            >
                <template #header>
                    <h3 class="text-white text-[36px] font-semibold self-center section-header uppercase">
                        Featured Schools
                    </h3>
                </template>
                <template #cta>
                    <button
                        class="bg-white px-4 py-2 rounded-sm border-2 border-[#002858] text-[#002858] text-[15px] font-medium cursor-pointer hover:text-[#0b1829] hover:border-2 hover:border-[#0b1829]"
                        @click="handleBrowseAllSchool"
                    >
                        View all schools
                    </button>
                </template>
            </SectionHeader>
            <div class="grid grid-cols-4 gap-[24px] w-full px-20 pt-8 ">
                <div
                    v-for="(school,index) in featuredSitesData"
                    :key="index"
                    class="col-span-1 bg-white cursor-pointer h-[470px] border-[0.5px]  border-black transition-all group hover:shadow-2xl"
                >
                    <SchoolCard
                        v-if="featuredSitesData"
                        :school-data="school"
                    />
                </div>
            </div>
        </div>


        <div class="py-20 px-20">
            <SearchableMap />
        </div>
    </div>
</template>
