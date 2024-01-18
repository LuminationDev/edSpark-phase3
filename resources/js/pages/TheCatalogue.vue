<script setup lang="ts">
import
    "@hennge/vue3-pagination/dist/vue3-pagination.css";

import VPagination from "@hennge/vue3-pagination";
import {Ref} from "vue";
import {computed, onMounted, ref, watch} from 'vue'

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import CatalogueFilterColumn from "@/js/components/catalogue/CatalogueFilterColumn.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import useErrorMessage from "@/js/composables/useErrorMessage";
import usePagination from "@/js/composables/usePagination";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {catalogueService} from "@/js/service/catalogueService";
import {CatalogueItemType} from "@/js/types/catalogueTypes";


const catalogueList: Ref<CatalogueItemType[] | []> = ref([]);
const categoryList = ref([])
const brandList = ref([])
const typeList = ref([])
const vendorList = ref([])

const selectedCategory = ref([])
const selectedBrand = ref([])
const selectedType = ref([])
const selectedVendor = ref([])
const {error,setError, clearError} = useErrorMessage()

const isProductsLoading = ref(false)
const {
    currentPage, perPage, totalPages, totalItems,
    handleChangePageNumber, updatePaginationData
} = usePagination(1, 30)

const showPagination = computed(() => {
    return totalPages.value > 1
})


onMounted(async () => {
    fetchCatalogue('category', selectedCategory.value, currentPage.value, perPage.value)
    catalogueService.fetchAllCategories().then(res => {
    })

    try {
        const [categoriesResponse, typesResponse, brandsResponse, vendorsResponse] = await Promise.all([
            catalogueService.fetchAllCategories(),
            catalogueService.fetchAllTypes(),
            catalogueService.fetchAllBrands(),
            catalogueService.fetchAllVendors()
        ]);
        categoryList.value = categoriesResponse.data.data.filter(Boolean)
        typeList.value = typesResponse.data.data.filter(Boolean);
        brandList.value = brandsResponse.data.data.filter(Boolean);
        vendorList.value = vendorsResponse.data.data.filter(Boolean);
    } catch (error) {
        // Handle errors here
        console.error('Error fetching data:', error);
    }

})

const fetchCatalogue = (field, category, page, perPage = 40) => {
    isProductsLoading.value = true
    clearError()
    catalogueService.fetchCatalogueByField(field, category, page, perPage).then(res => {
        isProductsLoading.value = false
        catalogueList.value = res.data.data.items
        updatePaginationData(res.data.data.pagination)
        updateOtherFilters(res.data.data.available_fields)
    }).catch(err =>{
        console.log(err)
        isProductsLoading.value = false
        if(err.response.status == 404){
            setError(404, 'No product found')
        }
    })
}


const updateOtherFilters = (available_fields) =>{
    const listOfKeys = Object.keys(available_fields)
    listOfKeys.forEach(key =>{
        if(key === 'brand'){
            brandList.value = available_fields[key]
        }else if(key === 'type'){
            typeList.value = available_fields[key]
        } else if(key === 'vendor'){
            vendorList.value = available_fields[key]
        }else if(key === 'categories'){
            categoryList.value = available_fields[key]
        }
    })
}
watch(selectedCategory, () => {
    fetchCatalogue('category', selectedCategory.value, currentPage.value, perPage.value)
})
watch(currentPage, () => {
    fetchCatalogue('category', selectedCategory.value, currentPage.value, perPage.value)

})

</script>

<template>
    <BaseLandingHero
        title="ICT Catalogue"
        title-paragraph="Explore"
    />
    <div class="cataloguePageOuterContainer grid grid-cols-4 mt-10">
        <div class="col-span-1 flex items-center flex-col gap-2">
            <CatalogueFilterColumn
                v-model:brand-list="brandList"
                v-model:type-list="typeList"
                v-model:vendor-list="vendorList"
                v-model:category-list="categoryList"
                v-model:selected-brand="selectedBrand"
                v-model:selected-type="selectedType"
                v-model:selected-vendor="selectedVendor"
                v-model:selected-category="selectedCategory"
            />
        </div>
        <div v-if="error.status ">
            {{ error.message }}
        </div>
        <div
            v-else-if="!isProductsLoading && !error.status"
            class="col-span-3 productPanel"
        >
            <div class="grid grid-cols-2 gap-4 place-items-center lg:!grid-cols-3 xl:!grid-cols-4">
                <div
                    v-for="(item,index) in catalogueList"
                    :key="index"
                    class="border-[1px] grid place-items-center rounded w-48"
                >
                    <img
                        :src="catalogueImageURL + item.image"
                        class="w-24"
                        :alt="'Photo of ' + item.name"
                    >

                    <div class="font-semibold productName">
                        {{ item.name }}
                    </div>
                    <div> {{ item.category }}</div>
                    <div> {{ '$' + item.price_inc_gst }}</div>
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


        <Loader
            v-else
            loader-type="small"
            loader-message="loading products"
        />
    </div>
</template>
