<script setup lang="ts">
import {onMounted, ref} from "vue";
import { useRoute, useRouter } from "vue-router";

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import GenericMultiSelectFilter from "@/js/components/search/hardware/GenericMultiSelectFilter.vue";
import LabelFiltersSearchPage from "@/js/components/search/LabelFiltersSearchPage.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import { LandingHeroText } from "@/js/constants/PageBlurb";

const route = useRoute();
const router = useRouter();

const currentPage = ref(1)
const perPage = ref(9)

const adviceFilterList = [
    { name: "Digital Adoption Group", value: "DAG" },
    { name: "Partner", value: "Partner" },
    { name: "Your Classroom", value: "Your Classroom" },
    { name: "Your Work", value: "Your Work" },
    { name: "Your Learning", value: "Your Learning" },
];

const filterObject = ref({});

const handleFilter = (filters, dataPath) => {
    filterObject.value[dataPath] = filters.map((filter) => filter.value).flat(1);
};

const preselectedFilterObject = ref(null as {
    name: string;
    value: string | string[];
} | null);

if (route.params || route.params.filter) {
    switch (route.params.filter) {
    case "dag":
        preselectedFilterObject.value = {
            name: "Digital Adoption Group",
            value: "DAG",
        };
        break;
    case "educators":
        preselectedFilterObject.value = {
            name: "Educators",
            value: ["Your Classroom", "Your Work", "Your Learning"],
        };
        break;
    case "partner":
        preselectedFilterObject.value = { name: "Partner", value: "Partner" };
        break;
    default:
        router.push("/browse/guide");
    }
}


onMounted(() => {
    fetchData(perPage.value, currentPage.value);
});

const fetchData = async (perPage, currentPage) => {
    const response = await fetch(API_ENDPOINTS.ADVICE.FETCH_ADVICE_POSTS, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            perPage: perPage,
            currentPage: currentPage
        })
    });
    return await response.json();
};

const handlePaginationChange = ({ perPage, currentPage }) => {
    fetchData(perPage, currentPage);
};




</script>

<template>
    <BaseSearch
        search-type="guide"
        :live-filter-object="filterObject"
        :hero-title="LandingHeroText['guideSearch']['title']"
        :hero-subtitle="LandingHeroText['guideSearch']['subtitle']"
        @pagination-change="handlePaginationChange"
    >
        <template #filterBar>
            <GenericMultiSelectFilter
                id="adviceFilter"
                placeholder="Filter by type"
                :filter-list="adviceFilterList"
                data-path="type"
                :preselected="preselectedFilterObject"
                @transmit-selected-filters="handleFilter"
            />
        </template>
        <template #additionalFilters>
            <LabelFiltersSearchPage
                labels-type="advice"
                @emit-filter-to-individual-search-page="handleFilter"
            />
        </template>
    </BaseSearch>
</template>
