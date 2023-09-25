<script setup lang="ts">
import {storeToRefs} from "pinia";
import useSWRV from "swrv";
import {computed, onBeforeMount, ref} from 'vue'

import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";
import SchoolsSearchableMap from '@/js/components/schools/schoolMap/SchoolsSearchableMap.vue';
import Loader from '@/js/components/spinner/Loader.vue';
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {swrvOptions} from "@/js/constants/swrvConstants";
import {axiosSchoolFetcherParams} from "@/js/helpers/fetcher";
import {useSchoolsStore} from "@/js/stores/useSchoolsStore";
import {useUserStore} from "@/js/stores/useUserStore";

const userStore = useUserStore()
const schoolStore = useSchoolsStore()
const {schools} = storeToRefs(schoolStore)
const showLoading = ref(true)

const {
    data: featuredSites,
    error: featuredSitesError,
    isValidating: isValidatingFeatured
} = useSWRV(API_ENDPOINTS.SCHOOL.FETCH_FEATURED_SCHOOL, axiosSchoolFetcherParams(userStore.getUserRequestParam), swrvOptions)

const schoolsAvailable = computed(() => {
    return Boolean(schools.value.length)
})

onBeforeMount(async () => {
    schoolStore.loadSchools().then(() => {

    }).catch(() => {
        schools.value = []
    }).finally(() => {
        showLoading.value = false
    })

})


</script>
<template>
    <div class="featuredClassContainer">
        <CarouselGenerator
            :show-count="3"
            data-type="school"
            :data-array="featuredSites ? featuredSites : []"
        />
    </div>
    <div
        v-if="schoolsAvailable"
        class="px-5 xl:!px-20"
    >
        <SchoolsSearchableMap
            :key="schoolsAvailable"
            :schools="schools"
            :schools-available="schoolsAvailable"
        />
    </div>

    <div
        v-else-if="showLoading"
        class="flex w-full"
    >
        <Loader
            :loader-color="'#0072DA'"
            :loader-message="'Map loading'"
        />
    </div>
</template>
