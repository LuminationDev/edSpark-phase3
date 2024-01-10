<script setup lang="ts">
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from "vue";
import {useRoute, useRouter} from "vue-router";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import SchoolsRobot from "@/js/components/svg/schoolsRobot/schoolsRobot.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {schoolPartnerTech,schoolTech} from "@/js/constants/schoolTech";
import {lowerSlugify} from "@/js/helpers/stringHelpers";
import {schoolService} from "@/js/service/schoolService";
import {useSchoolsStore} from "@/js/stores/useSchoolsStore";

const route = useRoute()
const router = useRouter()

const combinedSchoolTech = [...schoolTech,...schoolPartnerTech];
const {allSchools} = storeToRefs(useSchoolsStore())

onMounted(() =>{
    schoolService.fetchAllSchools().then(res =>{
        console.log(res)
        allSchools.value = res
    })

})


const schoolFilterList = schoolService.getSchoolFilterList()

const schoolTechFilterList = computed(() =>{
    return combinedSchoolTech.map(item =>{
        return {name: item.name, value: item.name}
    })
})

const filterObject = ref({})

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map(filter => filter.value)
}

const preselectedFilterObject = ref(null as {name: string, value: string | string[]} | null);


if (route.params && route.params.filter) {
    const filterNameSlug = lowerSlugify(route.params.filter);

    const matchingFilter = schoolFilterList.find(filter => filterNameSlug === lowerSlugify(filter.name));

    if (matchingFilter) {
        preselectedFilterObject.value = matchingFilter;
    } else {
        router.push('/browse/school');
    }
}


</script>

<template>
    <BaseSearch
        search-type="school"
        :resource-list="allSchools"
        :live-filter-object="filterObject"
        :hero-title="LandingHeroText['school']['title']"
        :hero-subtitle="LandingHeroText['school']['subtitle']"
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                id="schoolType"
                placeholder="Filter by type"
                :filter-list="schoolFilterList"
                data-path="site_type_code"
                :preselected="preselectedFilterObject"

                @transmit-selected-filters="handleFilter"
            />
            <GenericMultiSelectFilter
                id="techType"
                placeholder="Filter by tech"
                :filter-list="schoolTechFilterList"
                data-path="tech_used"
                @transmit-selected-filters="handleFilter"
            />
        </template>
        <template #robot>
            <SchoolsRobot
                class="absolute top-10 left-36"
            />
        </template>
    </BaseSearch>
</template>
