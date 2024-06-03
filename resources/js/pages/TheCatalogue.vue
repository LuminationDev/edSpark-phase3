<script setup lang="ts">
import "@hennge/vue3-pagination/dist/vue3-pagination.css";

import VPagination from "@hennge/vue3-pagination";
import {watchDebounced} from "@vueuse/core";
import {storeToRefs} from "pinia";
import {computed, onMounted, ref, watch} from "vue";
import {useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import CatalogueCard from "@/js/components/catalogue/CatalogueCard.vue";
import CatalogueComparisonBanner from "@/js/components/catalogue/cataloguecomparison/CatalogueComparisonBanner.vue";
import CatalogueFilterColumn from "@/js/components/catalogue/CatalogueFilterColumn.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import useErrorMessage from "@/js/composables/useErrorMessage";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {catalogueService} from "@/js/service/catalogueService";
import {useCataloguePaginationStore} from "@/js/stores/useCataloguePaginationStore";
import {useCatalogueStore} from "@/js/stores/useCatalogueStore";
import {useQuoteStore} from "@/js/stores/useQuoteStore";
import {CatalogueFilterField} from "@/js/types/catalogueTypes";


const catalogueStore = useCatalogueStore();
const quoteStore = useQuoteStore()

const {
    catalogueList,
    categoryList,
    brandList,
    typeList,
    vendorList,
    processorList,
    memoryList,
    storageList,
    primaryFilter,
    selectedCategory,
    selectedBrand,
    selectedType,
    selectedVendor,
    selectedProcessor,
    selectedMemory,
    selectedStorage,
    priceRange,
    searchKeyword
} = storeToRefs(catalogueStore)

const cataloguePaginationStore = useCataloguePaginationStore()
onMounted(async () => {
    await quoteStore.initializeQuote()
})

const isProductsLoading = ref(false)
const isFilterLoading = ref(false)
const {error, setError, clearError} = useErrorMessage()
const {
    currentPage, perPage, totalPages, totalItems,
} = storeToRefs(cataloguePaginationStore)

const router = useRouter()
const showPagination = computed(() => {
    return totalPages.value > 1
})


const primarySelectedValues = computed(() => {
    if (primaryFilter.value == CatalogueFilterField.Type) return selectedType.value
    if (primaryFilter.value == CatalogueFilterField.Vendor) return selectedVendor.value
    if (primaryFilter.value == CatalogueFilterField.Brand) return selectedBrand.value
    if (primaryFilter.value == CatalogueFilterField.Category) return selectedCategory.value
})

const additionalFilters = computed(() => {
    return {
        type: selectedType.value,
        vendor: selectedVendor.value,
        brand: selectedBrand.value,
        category: selectedCategory.value,
        price: priceRange.value,
        processor: selectedProcessor.value,
        memory: selectedMemory.value,
        storage: selectedStorage.value
    }
})

onMounted(async () => {
    try {
        if (primaryFilter.value) {
            return;
        }

        isFilterLoading.value = true
        const cataloguesResult = await fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Category, selectedCategory.value, additionalFilters.value, currentPage.value, perPage.value)
    } catch (error) {
        // Handle errors here
        console.error('Error fetching data:', error);
    } finally {
        isFilterLoading.value = false
    }
})

/* not including the primary filter and in the backend can be iterated with key value pair
    "addtFilters": {
        "type": ["value1", "value2"],
        "category": ["value3"]
    },
 */


const fetchCatalogue = async (field, value, additional, page, perPage = 16) => {
    if (!catalogueList.value.length) {
        isProductsLoading.value = true
    }
    return catalogueService.fetchCatalogueByField(field, value, additional, page, perPage, searchKeyword.value)
        .then(res => {
            clearError()
            return res.data.data

        })
        .catch(err => {
            console.log(err)
            isProductsLoading.value = false
            if (err.response.status == 404) {
                setError(404, 'No product found')
            }
        }).finally(() => {
            isProductsLoading.value = false
        })
}
const fetchCatalogueAndUpdateOtherFilters = async (field, value, additional, page, perPage = 16) => {
    isProductsLoading.value = true
    clearError()
    const catalogueFetchResult = await fetchCatalogue(field, value, additional, page, perPage)
    catalogueList.value = catalogueFetchResult.items
    isProductsLoading.value = false
    if (catalogueFetchResult.pagination) {
        cataloguePaginationStore.updatePaginationData(catalogueFetchResult.pagination)
    }

    if (catalogueFetchResult.available_fields) {
        updateOtherFilters(catalogueFetchResult.available_fields)
    }

}


const updateOtherFilters = (available_fields) => {
    const listOfKeys = Object.keys(available_fields)
    listOfKeys.forEach(key => {
        if (key === 'brand') {
            brandList.value = available_fields[key].filter(Boolean)
        } else if (key === 'type') {
            typeList.value = available_fields[key].filter(Boolean)
        } else if (key === 'vendor') {
            vendorList.value = available_fields[key].filter(Boolean)
        } else if (key === 'category') {
            categoryList.value = available_fields[key].filter(Boolean)
        } else if (key === 'processor') {
            processorList.value = available_fields[key].filter(Boolean)
        } else if (key === 'storage') {
            storageList.value = available_fields[key].filter(Boolean)
        } else if (key === 'memory') {
            memoryList.value = available_fields[key].filter(Boolean)
        }
    })
}

watchDebounced(selectedCategory, async () => {
    if (!selectedBrand.value.length &&
        !selectedType.value.length &&
        !selectedVendor.value.length &&
        !selectedProcessor.value.length &&
        !selectedMemory.value.length &&
        !selectedStorage.value.length
    ) {
        primaryFilter.value = CatalogueFilterField.Category
        currentPage.value = 1
        await fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Category, selectedCategory.value, additionalFilters.value, currentPage.value, perPage.value)
    } else {
        await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)
    }
}, {debounce: 600})

watchDebounced(selectedBrand, async () => {
    if (!selectedVendor.value.length &&
        !selectedType.value.length &&
        !selectedCategory.value.length &&
        !selectedProcessor.value.length &&
        !selectedMemory.value.length &&
        !selectedStorage.value.length) {
        primaryFilter.value = CatalogueFilterField.Brand;
        await fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Brand, selectedBrand.value, additionalFilters.value, currentPage.value, perPage.value)
    } else {
        await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)

    }
}, {debounce: 600})
watchDebounced(selectedType, async () => {
    if (!selectedBrand.value.length &&
        !selectedVendor.value.length &&
        !selectedCategory.value.length &&
        !selectedProcessor.value.length &&
        !selectedMemory.value.length &&
        !selectedStorage.value.length) {
        primaryFilter.value = CatalogueFilterField.Type;
        await fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Type, selectedType.value, additionalFilters.value, currentPage.value, perPage.value)
    } else {
        await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)

    }
}, {debounce: 600})
watchDebounced(selectedVendor, async () => {
    if (!selectedBrand.value.length &&
        !selectedType.value.length &&
        !selectedCategory.value.length &&
        !selectedProcessor.value.length &&
        !selectedMemory.value.length &&
        !selectedStorage.value.length
    ) {
        primaryFilter.value = CatalogueFilterField.Vendor;
        await fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Vendor, selectedVendor.value, additionalFilters.value, currentPage.value, perPage.value)
    } else {
        await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)
    }
}, {debounce: 600})


watchDebounced(selectedProcessor, async () => {
    if (!selectedBrand.value.length &&
        !selectedType.value.length &&
        !selectedVendor.value.length &&
        !selectedCategory.value.length &&
        !selectedMemory.value.length &&
        !selectedStorage.value.length
    ) {
        primaryFilter.value = CatalogueFilterField.Processor
        currentPage.value = 1
        await fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Processor, selectedProcessor.value, additionalFilters.value, currentPage.value, perPage.value)
    } else {
        await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)
    }
}, {debounce: 600})


watchDebounced(selectedMemory, async () => {
    if (!selectedBrand.value.length &&
        !selectedType.value.length &&
        !selectedVendor.value.length &&
        !selectedProcessor.value.length &&
        !selectedCategory.value.length &&
        !selectedStorage.value.length
    ) {
        primaryFilter.value = CatalogueFilterField.Memory
        currentPage.value = 1
        await fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Memory, selectedMemory.value, additionalFilters.value, currentPage.value, perPage.value)
    } else {
        await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)
    }
}, {debounce: 600})


watchDebounced(selectedStorage, async () => {
    if (!selectedBrand.value.length &&
        !selectedType.value.length &&
        !selectedVendor.value.length &&
        !selectedProcessor.value.length &&
        !selectedMemory.value.length &&
        !selectedCategory.value.length
    ) {
        primaryFilter.value = CatalogueFilterField.Storage
        currentPage.value = 1
        await fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Storage, selectedStorage.value, additionalFilters.value, currentPage.value, perPage.value)
    } else {
        await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)
    }
}, {debounce: 600})


watch([currentPage, perPage], async () => {
    await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)
})
const handleClickCatalogueCard = (reference) => {
    router.push({
        name: 'catalogue-single', params: {
            ref: reference
        }
    })

}

const handlePriceChange = async () => {
    await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)
}

const handleSearchTermChanged = async (newTerm) => {
    console.log(newTerm)
    searchKeyword.value = newTerm
    await fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, additionalFilters.value, currentPage.value, perPage.value)

}
</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText['catalogue']['title']"
        :title-paragraph="LandingHeroText['catalogue']['subtitle']"
        swoosh-color="teal"
    />
    <div class="cataloguePageOuterContainer grid grid-cols-10 mt-16">
        <div class="col-span-2 flex flex-col gap-2 ml-8 pr-8">
            <CatalogueFilterColumn
                v-model:brand-list="brandList"
                v-model:type-list="typeList"
                v-model:vendor-list="vendorList"
                v-model:category-list="categoryList"
                v-model:processor-list="processorList"
                v-model:memory-list="memoryList"
                v-model:storage-list="storageList"
                v-model:selected-brand="selectedBrand"
                v-model:selected-type="selectedType"
                v-model:selected-vendor="selectedVendor"
                v-model:selected-category="selectedCategory"
                v-model:selected-processor="selectedProcessor"
                v-model:selected-memory="selectedMemory"
                v-model:selected-storage="selectedStorage"
                v-model:price-range="priceRange"
                v-model:per-page="perPage"
                :is-filter-loading="isFilterLoading"
                @price-changed="handlePriceChange"
                @search-term-changed="handleSearchTermChanged"
            />
        </div>
        <div v-if="error.status ">
            {{ error.message }}
        </div>
        <div
            v-else-if="!isProductsLoading && !error.status"
            class="col-span-8 productPanel"
        >
            <div
                class="2xl:!grid-cols-4 grid grid-cols-1 gap-x-2 gap-y-8 place-items-center lg:!grid-cols-2 xl:!grid-cols-3"
            >
                <template
                    v-for="(item,index) in catalogueList"
                    :key="item.unique_reference + index"
                >
                    <CatalogueCard
                        :cat-item="item"
                        :click-callback="handleClickCatalogueCard"
                    />
                </template>
            </div>
            <CatalogueComparisonBanner />

            <div
                v-if="showPagination"
                class="cataloguePagination flex justify-center mt-12 text-lg"
            >
                <v-pagination
                    v-model="currentPage"
                    :range-size="1"
                    :pages="totalPages"
                    active-color="#DCEDFF"
                    :hide-first-button="true"
                    :hide-last-button="true"
                    @update:model-value="cataloguePaginationStore.handleChangePageNumber"
                />
            </div>
        </div>

        <div
            v-else
            class="col-span-8 flex justify-center items-start flex-row mt-24"
        >
            <Loader
                loader-type="small"
                loader-message="Loading products"
            />
        </div>
    </div>
</template>
<style lang="scss">

.cataloguePagination {
    .Pagination {
        padding-left: 16px;
        padding-right: 16px;

        .PaginationControl:first-of-type {
            margin-right: auto;
        }

        .PaginationControl:last-of-type {
            margin-left: auto;
        }

        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
}
</style>
