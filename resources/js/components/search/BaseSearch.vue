<script setup>
import
    "@hennge/vue3-pagination/dist/vue3-pagination.css";

import VPagination from "@hennge/vue3-pagination";
import {computed, ref, watch} from 'vue'

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import SearchBar from "@/js/components/browseschools/SearchBar.vue";
import CardLoading from "@/js/components/card/CardLoading.vue";
import EventsCard from "@/js/components/events/EventsCard.vue";
import HardwareCard from "@/js/components/hardware/HardwareCard.vue";
import PartnerCard from "@/js/components/partners/PartnerCard.vue";
import SchoolCard from "@/js/components/schools/SchoolCard.vue";
import SoftwareCard from "@/js/components/software/SoftwareCard.vue";
import usePagination from "@/js/composables/usePagination";
import {SearchTitleByType} from "@/js/constants/PageBlurb";
import {guid} from "@/js/helpers/guidGenerator";

const props = defineProps({
    resourceList: {
        type: Array,
        required: true
    },
    searchType: {
        type: String,
        required: true
    },
    liveFilterObject: {
        type: Object,
        required: true
    },
    heroTitle: {
        type: String,
        required: true
    },
    heroSubtitle: {
        type: String,
        required: true
    },
    heroBackgroundColor: {
        type: String,
        required: false,
        default: 'darkTeal'
    },
    customView: {
        type: Boolean,
        required: false,
        default: false
    }
});

const { currentPage, perPage, handleChangePageNumber, updatePaginationData } = usePagination(1, 9);
const filterTerm = ref('');

const filteredTermData = computed(() => {
    if (!props.resourceList) return [];

    return props.resourceList.reduce((acc, resource) => {
        const attributesToCheck = ['name', 'title'];

        if (filterTerm.value.length < 1 || attributesToCheck.some(attr => resource[attr] && resource[attr].toLowerCase().includes(filterTerm.value))) {
            resource['key'] = guid();
            acc.push(resource);
        }
        return acc;
    }, []);
});

const handleSearchTerm = (term) => {
    filterTerm.value = term.toLowerCase();
}

watch(props.liveFilterObject, () => {
    currentPage.value = 1;
});

const paginatedFilteredData = computed(() => {
    const startIndex = (currentPage.value - 1) * perPage.value;
    return filteredTermData.value.slice(startIndex, startIndex + perPage.value);

});

// Compute total pages based on filtered data and items per page
const totalPages = computed(() => {
    return Math.ceil(filteredTermData.value.length / perPage.value);
});

const showPagination = computed(() => {
    return filteredTermData.value.length > perPage.value;
});

const formattedSearchTitle = computed(() => {
    return SearchTitleByType[props.searchType];
});

const formattedSearchBlurb = computed(() => {
    if (['school'].includes(props.searchType))
        return "Discover more about how schools in your area are embracing digital technology, and draw inspiration for your own classroom.";
    else
        return "Discover inspiration for your own classroom";
});

console.log("Here is the value for: " + currentPage.value)
console.log("Here is the value for: " + totalPages.value)
</script>

<template>
    <BaseLandingHero
        :title="props.heroTitle"
        :title-paragraph="props.heroSubtitle"
        :background-color="props.heroBackgroundColor"
        :swoosh-color="heroBackgroundColor"
    />
    <div
        class="browse-schools-container flex items-center flex-col px-12 py-16"
    >
        <div class="flex flex-col search-filter-element w-full">
            <div class="mb-8 pr-4 search-filter-heading">
                <h3 class="font-semibold text-3xl">
                    Browse all {{ formattedSearchTitle }}
                </h3>
                <p class="pr-10 pt-6 text-lg">
                    {{ formattedSearchBlurb }}
                </p>
            </div>
            <div class="grid grid-cols-1 gap-4 w-full lg:!grid-cols-3">
                <div class="flex flex-col search-filter-components">
                    <SearchBar
                        :placeholder="`Type in ${searchType} name`"
                        class="[&>p]:!ml-0 [&>p]:font-medium [&>p]:text-lg mb-4 lg:mb-0 md:[&>p]:text-xl"
                        @emit-search-term="handleSearchTerm"
                    />
                </div>
                <slot name="filterBar" />
                <slot name="additionalFilters" />
            </div>
            <div class="my-4 searchResults text-base text-center">
                <span v-if="filteredTermData">
                    {{ String(filteredTermData.length) + " search " + (filteredTermData.length > 1 ? "results" : "result") }}
                </span>
                <span v-else>
                    Filtered data is not available
                </span>
            </div>
            <div
                v-if="resourceList && resourceList.length &&
                    props.customView"
                class="customViewContainer"
            >
                <slot
                    name="customViewSlot"
                    :filtered-data="filteredTermData"
                />
            </div>
            <div
                v-else-if="resourceList && resourceList.length && !props.customView"
                id="resourceResult"
                class="
                    grid
                    grid-cols-1
                    gap-10
                    place-items-center
                    pt-10
                    resourceResult
                    w-full
                    md:!grid-cols-2
                    xl:!gap-12
                    xl:!grid-cols-3
                    "
            >
                <template
                    v-for="(data) in paginatedFilteredData"
                    :key="data['key']"
                >
                    <template
                        v-if="searchType === 'guide'"
                    >
                        <div
                            :key="data.id"
                            class="group h-[470px] max-w-[350px] transition-all w-full hover:shadow-2xl lg:!max-w-[400px]"
                        >
                            <AdviceCard
                                :key="data.id"
                                :data="data"
                                :show-icon="true"
                            />
                        </div>
                    </template>
                    <template v-else-if="searchType === 'software'">
                        <div
                            :key="data.id"
                            class="group h-[470px] max-w-[350px] transition-all w-full hover:shadow-2xl lg:!max-w-[400px]"
                        >
                            <SoftwareCard
                                :key="data.id"
                                :data="data"
                            />
                        </div>
                    </template>
                    <template v-else-if="searchType === 'hardware'">
                        <div
                            :key="data.id"
                            class="group h-[470px] max-w-[350px] transition-all w-full hover:shadow-2xl lg:!max-w-[400px]"
                        >
                            <HardwareCard
                                :key="data.id"
                                :data="data"
                            />
                        </div>
                    </template>
                    <template v-else-if="searchType === 'school'">
                        <div
                            :key="data.id"
                            class="group h-[470px] max-w-[350px] transition-all w-full hover:shadow-2xl lg:!max-w-[400px]"
                        >
                            <SchoolCard
                                class="mx-auto"
                                :data="data"
                            />
                        </div>
                    </template>
                    <template v-else-if="searchType === 'partner'">
                        <div
                            :key="data.id"
                            class="group h-[470px] max-w-[350px] transition-all w-full hover:shadow-2xl lg:!max-w-[400px]"
                        >
                            <PartnerCard
                                :data="data"
                            />
                        </div>
                    </template>
                    <template v-else-if="searchType === 'event'">
                        <div
                            :key="data.id"
                            class="
                                border-black
                                group
                                h-[470px]
                                max-w-[350px]
                                transition-all
                                w-full
                                hover:shadow-2xl
                                lg:!max-w-[400px]
                                "
                        >
                            <EventsCard
                                :data="data"
                                :show-icon="true"
                            />
                        </div>
                    </template>
                </template>

                <div
                    v-if="filteredTermData && filteredTermData.length <= 0"
                    class="col-span-1 font-semibold text-xl md:!col-span-2 lg:!col-span-3"
                >
                    No search result
                </div>
            </div>
            <div
                v-else
                class="w-full"
            >
                <CardLoading
                    :number-per-row="3"
                    :number-of-rows="2"
                />
            </div>
        </div>

        <div
            v-if="showPagination && !customView"
            class="BaseSearchPaginationContainer flex justify-center mt-12 text-lg"
        >
            <v-pagination
                v-model="currentPage"
                :range-size="1"
                :pages="totalPages"
                active-color="#DCEDFF"
                @update:model-value="handleChangePageNumber"
            />
        </div>
    </div>
</template>
<style lang="scss">


/* MB added the below to tidy up responsive nav bars */
@media screen and (max-width: 767px) {
    .search-filter-element {
        flex-direction: column;
    }
}

// @media screen and (max-width: 510px) {
//     #searchIcon {
//         margin-left: 0.5rem !important;
//         top: 0.1rem !important;
//     }
// }

.BaseSearchPaginationContainer {

    .Pagination {
        font-size: large;

        .PaginationControl {

            .Control {
                height: 35px;
                width: 35px;
            }
        }

        li {

            button {
                font-size: 24px;
                margin-left: 16px;
                margin-right: 16px;

            }

            .Page,
            .Page-active {
                border: 1px transparent solid;
                padding: 16px;
            }

            .Page:hover {
                border: 1px #339999 solid;

            }


        }
    }
}
</style>

