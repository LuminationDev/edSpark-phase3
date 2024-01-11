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
import {LandingHeroText, SearchTitleByType} from "@/js/constants/PageBlurb";
import {guid} from "@/js/helpers/guidGenerator";
import {findNestedKeyValue} from "@/js/helpers/objectHelpers";


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
        default: 'teal'
    }
})

const filterTerm = ref('')

const filteredTermData = computed(() => {
    if (!props.resourceList) return [];

    return props.resourceList.reduce((acc, resource) => {
        // List of possible attribute names to check - here because different objects has different field
        const attributesToCheck = ['name', 'title'];

        // If filterTerm is empty or any attribute matches, add the resource to the accumulated array
        if (filterTerm.value.length < 1 || attributesToCheck.some(attr => resource[attr] && resource[attr].toLowerCase().includes(filterTerm.value))) {
            // Generate a key for each resource
            resource['key'] = guid();
            acc.push(resource);
        }
        return acc;
    }, []);
});


const handleSearchTerm = (term) => {
    filterTerm.value = term.toLowerCase()
}


// Function to filter the products based on the filterBy object
const filterProducts = (products, filterBy) => {
    // Check if filterBy object is empty
    const totalValuesCount = [].concat(...Object.values(filterBy)).length;
    if (totalValuesCount === 0) {
        return products;
    }
    return products.filter(product => {
        return Object.entries(filterBy).every(([key, filterValues]) => {
            // If filterValues is empty for this key, then move on to next key
            if (filterValues.length === 0) return true;

            const productValue = findNestedKeyValue(product, key).flat();

            // Check if any product value matches the filter values
            return productValue.some(value => {
                return filterValues.includes(value) || (value ? filterValues.includes(value.name) : false);
            });
        });
    });

}

// set a watcher to reset page to the first page when filters are changed
watch(props.liveFilterObject, () => {
    page.value = 1
})

const filteredData = computed(() => {
    if (Object.values(props.liveFilterObject).length === 0) return filteredTermData.value
    return filterProducts(filteredTermData.value, props.liveFilterObject)
})

// pagination code below
const page = ref(1)
const numberOfItemsPerPage = 9

const handleChangePageNumber = (newPageNumber) => {
    page.value = newPageNumber
    const scrollToTop = () => {
        window.scrollTo({top: 0, behavior: 'smooth'})
    }
    scrollToTop()

}

const numberOfAvailablePages = computed(() => {
    return Math.ceil(filteredData.value.length / numberOfItemsPerPage)
})

const paginatedFilteredData = computed(() => {
    if (page.value === numberOfAvailablePages.value) {
        //show the rest without hard limit
        return filteredData.value.slice((page.value - 1) * numberOfItemsPerPage)
    } else {

        return filteredData.value.slice((page.value - 1) * numberOfItemsPerPage, page.value * numberOfItemsPerPage)
    }
})

const showPagination = computed(() => {
    return filteredData.value.length > numberOfItemsPerPage
})

const formattedSearchTitle = computed(() => {
    return SearchTitleByType[props.searchType]
})


const formattedSearchBlurb = computed(() => {

    if (['school'].includes(props.searchType))
        return "Discover more about how schools in your area are " +
            "embracing digital technology, and draw inspiration " +
            "for your own classroom."


    else return "Discover inspiration for your own classroom."


})

</script>

<template>
    <BaseLandingHero
        :title="props.heroTitle"
        :title-paragraph="props.heroSubtitle"
        :background-color="props.heroBackgroundColor"
    >
        <template #robotIllustration>
            <slot name="robot" />
        </template>
    </BaseLandingHero>
    <div
        class="browse-schools-container flex items-center flex-col px-12 py-16"
    >
        <div class="flex flex-col search-filter-element w-full">
            <div class="mb-8 pr-4 search-filter-heading">
                <h3 class="font-semibold text-3xl">
                    Browse all {{ formattedSearchTitle }}
                </h3>
                <p class="pr-10 pt-6">
                    {{ formattedSearchBlurb }}
                </p>
            </div>

            <!--            <div class="flex flex-col search-filter-components">-->
            <!--                <SearchBar-->
            <!--                    :placeholder="`Type in ${searchType} name`"-->
            <!--                    @emit-search-term="handleSearchTerm"-->
            <!--                />-->
            <!--                <div class="">-->
            <!--                    <slot name="filterBar" />-->
            <!--                </div>-->
            <!--            </div>-->
        </div>
        <div class="grid grid-cols-3 gap-4 w-full">
            <div class="flex flex-col search-filter-components">
                <SearchBar
                    :placeholder="`Type in ${searchType} name`"
                    @emit-search-term="handleSearchTerm"
                />
                <div class="" />
            </div>
            <slot name="filterBar" />
            <slot name="additionalFilters" />
        </div>
        <div class="my-4 searchResults text-base">
            {{ String(filteredData.length) + " search " + (filteredData.length > 1 ? "results" : "result") }}
        </div>
        <div
            v-if="resourceList"
            id="resourceResult"
            class="grid grid-cols-1 gap-10 place-items-center pt-10 resourceResult w-full md:!grid-cols-2 xl:!gap-12 xl:!grid-cols-3"
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
                        class="group h-[470px] max-w-[350px] transition-all w-full  hover:shadow-2xl lg:!max-w-[400px]"
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
                        class="group h-[470px] max-w-[350px] transition-all w-full hover:shadow-2xl lg:!max-w-[400px]]"
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
                v-if="filteredData.length <= 0"
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
        v-if="showPagination"
        class="BaseSearchPaginationContainer flex justify-center mt-12 text-lg"
    >
        <v-pagination
            v-model="page"
            :range-size="1"
            :pages="numberOfAvailablePages"
            active-color="#DCEDFF"
            @update:model-value="handleChangePageNumber"
        />
    </div>
</template>
<style lang="scss">


/* MB added the below to tidy up responsive nav bars */
@media screen and (max-width: 767px) {
    .search-filter-element {
        flex-direction: column;
    }
}


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

