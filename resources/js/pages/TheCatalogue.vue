<script setup>
import
    "@hennge/vue3-pagination/dist/vue3-pagination.css";

import VPagination from "@hennge/vue3-pagination";
import {computed, onMounted, ref, watch} from 'vue'

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import usePagination from "@/js/composables/usePagination";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {catalogueService} from "@/js/service/catalogueService";

const catalogueList = ref([]);
const categoryList = ref([])

const currentCategory = ref('standard')
const isProductsLoading = ref(false)
const {
    currentPage, perPage, totalPages, totalItems,
    handleChangePageNumber, updatePaginationData
} = usePagination(1,30)

const showPagination = computed(() =>{
    return totalPages.value > 1
})


onMounted(async () => {
    fetchCatalogue('category', currentCategory.value, currentPage.value, perPage)
    catalogueService.fetchAllCategories().then(res => {
        categoryList.value = res.data.data.filter(Boolean)
    })
})

const fetchCatalogue = (field, category, page, perPage = 40) => {
    isProductsLoading.value = true
    catalogueService.fetchCatalogueByField(field, category, page, perPage).then(res => {
        isProductsLoading.value = false
        catalogueList.value = res.data.data.items
        updatePaginationData(res.data.data.pagination)
    })
}

const handleClickCategory = (newCategory) => {
    currentCategory.value = newCategory
}

watch(currentCategory, () => {
    fetchCatalogue('category', currentCategory.value, currentPage.value, perPage)
})
watch(currentPage,() =>{
    fetchCatalogue('category', currentCategory.value, currentPage.value, perPage)

})

</script>

<template>
    <BaseLandingHero
        title="ICT Catalogue"
        title-paragraph="Explore"
    />
    <div class="cataloguePageOuterContainer grid grid-cols-4">
        <div class="col-span-1 flex items-center flex-col gap-4">
            <div
                v-for="(category,index) in categoryList"
                :key="index"
                class="border-2 catalogueCategoryItem cursor-pointer px-4 py-1 rounded w-44"
                :class="{'bg-main-teal/20' : category.toLowerCase() === currentCategory}"
                @click="() => handleClickCategory(category.toLowerCase())"
            >
                {{ category }}
            </div>
        </div>
        <div
            v-if="!isProductsLoading"
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
