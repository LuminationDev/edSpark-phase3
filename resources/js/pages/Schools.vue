<script setup>
import {onMounted, ref} from 'vue'
import axios from 'axios'
import SchoolsHero from '../components/schools/SchoolsHero.vue';
import SearchableMap from '../components/schools/SearchableMap.vue';
import SchoolCard from "@/js/components/schools/SchoolCard.vue";

const schoolsLoading = ref(false)
const serverURL = import.meta.env.VITE_SERVER_URL_API

// TODO- Create an API in the backend to get featured schools
// Currently fetching 4 random
const featuredSiteIds = [292,69,55,42]
const featuredSites = ref([])
const featuredSitesData = ref([])

onMounted(() =>{
    axios.get(`${serverURL}/fetchAllSchools`).then(res => {
        featuredSites.value = res.data.splice(0,4)
        featuredSitesData.value = featuredSites.value

    })
})

</script>
<template>
    <div>
        <SchoolsHero />
        <div class="px-[81px] py-20">
            <div class="grid grid-cols-4 gap-[24px] w-full">
                <div
                    v-for="(school,index) in featuredSitesData"
                    :key="index"
                    class="col-span-1 bg-white border-[0.5px] border-black cursor-pointer h-[470px] transition-all group hover:shadow-2xl"
                >
                    <SchoolCard
                        v-if="featuredSitesData"
                        :school-data="school"
                    />
                </div>
            </div>
        </div>

        <div class="px-[81px] py-20">
            <SearchableMap />
        </div>
    </div>
</template>
