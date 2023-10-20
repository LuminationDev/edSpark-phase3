<script setup lang="ts">
import {storeToRefs} from "pinia";
import {computed, onBeforeMount, ref} from 'vue'

import CarouselGenerator from "@/js/components/card/CarouselGenerator.vue";
import SchoolsSearchableMap from '@/js/components/schools/schoolMap/SchoolsSearchableMap.vue';
import Loader from '@/js/components/spinner/Loader.vue';
import {useSchoolsStore} from "@/js/stores/useSchoolsStore";

const schoolStore = useSchoolsStore()
const {schools} = storeToRefs(schoolStore)
const showLoading = ref(true)

const featuredSites = computed(() =>{
    console.log(schools.value.filter(school => school['isFeatured']))
    return schools.value.filter(school => school['isFeatured'])
})

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
