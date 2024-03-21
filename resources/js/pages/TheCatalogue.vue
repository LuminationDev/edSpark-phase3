<script setup lang="ts">
import "@hennge/vue3-pagination/dist/vue3-pagination.css";

import VPagination from "@hennge/vue3-pagination";
import {computed, onMounted, Ref, ref, watch} from "vue";
import {useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import CatalogueFilterColumn from "@/js/components/catalogue/CatalogueFilterColumn.vue";
import CataloguePerPageSelector from "@/js/components/catalogue/CataloguePerPageSelector.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import useErrorMessage from "@/js/composables/useErrorMessage";
import usePagination from "@/js/composables/usePagination";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {catalogueService} from "@/js/service/catalogueService";
import {CatalogueFilterField, CatalogueItemType} from "@/js/types/catalogueTypes";


const catalogueList: Ref<CatalogueItemType[] | []> = ref([]);

const categoryList = ref([])
const brandList = ref([])
const typeList = ref([])
const vendorList = ref([])

const selectedCategory = ref([])
const selectedBrand = ref([])
const selectedType = ref([])
const selectedVendor = ref([])


const isProductsLoading = ref(false)
const isFilterLoading = ref(false)
const {error, setError, clearError} = useErrorMessage()
const {
    currentPage, perPage, totalPages, totalItems,
    handleChangePageNumber, updatePaginationData
} = usePagination(1, 24)

const router = useRouter()
const showPagination = computed(() => {
    return totalPages.value > 1
})



// have a primary filte rhere
const primaryFilter: Ref<CatalogueFilterField | null> = ref(null)

const primarySelectedValues = computed(() => {
    if (primaryFilter.value == CatalogueFilterField.Type) return selectedType.value
    if (primaryFilter.value == CatalogueFilterField.Vendor) return selectedVendor.value
    if (primaryFilter.value == CatalogueFilterField.Brand) return selectedBrand.value
    if (primaryFilter.value == CatalogueFilterField.Category) return selectedCategory.value
})

onMounted(async () => {


    try {
        isFilterLoading.value = true
        const [categoriesResponse, typesResponse, brandsResponse, vendorsResponse, cataloguesResult] = await Promise.all([
            catalogueService.fetchAllCategories(),
            catalogueService.fetchAllTypes(),
            catalogueService.fetchAllBrands(),
            catalogueService.fetchAllVendors(),
            fetchCatalogue(CatalogueFilterField.Category, selectedCategory.value, currentPage.value, perPage.value)
        ]);
        categoryList.value = categoriesResponse.data.data.filter(Boolean)
        typeList.value = typesResponse.data.data.filter(Boolean);
        brandList.value = brandsResponse.data.data.filter(Boolean);
        vendorList.value = vendorsResponse.data.data.filter(Boolean);
        catalogueList.value = cataloguesResult.items
        if (cataloguesResult.pagination) {
            updatePaginationData(cataloguesResult.pagination)
            console.log(currentPage.value)
            console.log(totalPages.value)
        }
    } catch (error) {
        // Handle errors here
        console.error('Error fetching data:', error);
    } finally {
        isFilterLoading.value = false
    }
})

const fetchCatalogue = async (field, category, page, perPage = 24) => {
    isProductsLoading.value = true
    return catalogueService.fetchCatalogueByField(field, category, page, perPage)
        .then(res => {
            console.log('here resolved')
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
const fetchCatalogueAndUpdateOtherFilters = async (field, category, page, perPage = 24) => {
    isProductsLoading.value = true
    clearError()
    const catalogueFetchResult = await fetchCatalogue(field, category, page, perPage)
    console.log('after await')
    catalogueList.value = catalogueFetchResult.items
    isProductsLoading.value = false
    if (catalogueFetchResult.pagination) {
        updatePaginationData(catalogueFetchResult.pagination)
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
        }
    })
}

watch(selectedCategory, () => {
    if (selectedBrand.value.length === 0 && selectedType.value.length === 0 && selectedVendor.value.length === 0) {
        primaryFilter.value = CatalogueFilterField.Category
        fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Category, selectedCategory.value, currentPage.value, perPage.value)

    }
})

watch(selectedBrand, () => {
    if (selectedVendor.value.length === 0 && selectedType.value.length === 0 && selectedCategory.value.length === 0) {
        primaryFilter.value = CatalogueFilterField.Brand;
        fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Brand, selectedBrand.value, currentPage.value, perPage.value)
    }
})
watch(selectedType, () => {
    if (selectedBrand.value.length === 0 && selectedVendor.value.length === 0 && selectedCategory.value.length === 0) {
        primaryFilter.value = CatalogueFilterField.Type;
        fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Type, selectedType.value, currentPage.value, perPage.value)
    }
})
watch(selectedVendor, () => {
    if (selectedBrand.value.length === 0 && selectedType.value.length === 0 && selectedCategory.value.length === 0) {
        primaryFilter.value = CatalogueFilterField.Vendor;
        fetchCatalogueAndUpdateOtherFilters(CatalogueFilterField.Vendor, selectedVendor.value, currentPage.value, perPage.value)
    }
})


watch([currentPage, perPage], () => {
    console.log('primary filter is  ' + primaryFilter.value)
    fetchCatalogueAndUpdateOtherFilters(primaryFilter.value, primarySelectedValues.value, currentPage.value, perPage.value)
})

watch(primaryFilter.value, () => {
    currentPage.value = 1
})

const handleClickCatalogueCard = (reference) => {
    console.log('catalogue card clicked')
    router.push({
        name: 'catalogue-single', params: {
            ref: reference
        }
    })

}

</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText['catalogue']['title']"
        :title-paragraph="LandingHeroText['catalogue']['subtitle']"
        swoosh-color="teal"
    />
    <div class="cataloguePageOuterContainer grid grid-cols-4 mt-10">
        <div class="col-span-1 flex items-center flex-col gap-2">
            <CataloguePerPageSelector v-model="perPage" />
            <CatalogueFilterColumn
                v-model:brand-list="brandList"
                v-model:type-list="typeList"
                v-model:vendor-list="vendorList"
                v-model:category-list="categoryList"
                v-model:selected-brand="selectedBrand"
                v-model:selected-type="selectedType"
                v-model:selected-vendor="selectedVendor"
                v-model:selected-category="selectedCategory"
                :is-filter-loading="isFilterLoading"
            />
        </div>
        <div v-if="error.status ">
            {{ error.message }}
        </div>
        <div
            v-else-if="!isProductsLoading && !error.status"
            class="col-span-3 productPanel"
        >
            <div class="my-4 text-center totalItems">
                Total Items: {{ totalItems }}
            </div>
            <div class="2xl:!grid-cols-4 grid grid-cols-1 gap-4 place-items-center lg:!grid-cols-2 xl:!grid-cols-3">
                <div
                    v-for="(item,index) in catalogueList"
                    :key="index"
                    class="border-[1px] catalogueCard cursor-pointer grid place-items-center rounded w-72"
                    @click="() => handleClickCatalogueCard(item.unique_reference)"
                >
                    <img
                        :src="catalogueImageURL + item.image"
                        class="h-32 w-auto"
                        :alt="'Photo of ' + item.name"
                    >
                    <div class="grid grid-cols-2 place-items-start p-4 productInformationSection w-full">
                        <div class="font-medium">
                            Product Name:
                        </div>
                        <div class="font-light">
                            {{ item.name }}
                        </div>
                        <div class="font-medium">
                            Brand:
                        </div>
                        <div class="font-light">
                            {{ item.brand }}
                        </div>
                        <div class="font-medium">
                            Category:
                        </div>
                        <div class="font-light">
                            {{ item.category }}
                        </div>
                        <div class="font-medium">
                            Type:
                        </div>
                        <div class="font-light">
                            {{ item.type }}
                        </div>
                        <div class="font-medium">
                            Price inc gst:
                        </div>
                        <div class="font-light">
                            {{ '$' + item.price_inc_gst }}
                        </div>
                        <div class="font-medium">
                            Vendor:
                        </div>
                        <div class="font-light">
                            {{ item.vendor }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="showPagination"
                class="flex justify-center mt-12 text-lg"
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

        <div
            v-else
            class="col-span-3 flex justify-center items-start flex-row mt-24"
        >
            <Loader
                loader-type="small"
                loader-message="Loading products"
            />
        </div>
    </div>
</template>
