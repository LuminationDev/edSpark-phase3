<script setup>

import CatalogueFilterGroup from "@/js/components/catalogue/CatalogueFilterGroup.vue";
import Loader from "@/js/components/spinner/Loader.vue";

const props = defineProps({
    isFilterLoading:{
        type: Boolean,
        required: false,
        default: false
    }
})

const categoryList = defineModel('categoryList')
const typeList = defineModel('typeList')
const brandList = defineModel('brandList')
const vendorList = defineModel('vendorList')

const selectedCategory = defineModel('selectedCategory')
const selectedBrand = defineModel('selectedBrand')
const selectedType = defineModel('selectedType')
const selectedVendor = defineModel('selectedVendor')


</script>

<template>
    <div
        v-if="props.isFilterLoading"
        class="flex justify-center items-center"
    >
        <Loader
            loader-type="small"
            loader-message="Loading filters"
        />
    </div>
    <div
        v-else
        class="flex justify-center items-center flex-col"
    >
        <CatalogueFilterGroup
            v-if="categoryList.length"
            v-model="categoryList"
            v-model:selected="selectedCategory"
            title="Category"
        />
        <CatalogueFilterGroup
            v-if="typeList.length"
            v-model="typeList"
            v-model:selected="selectedType"
            title="Type"
        />
        <CatalogueFilterGroup
            v-if="brandList.length"
            v-model="brandList"
            v-model:selected="selectedBrand"
            title="Brand"
        />
        <CatalogueFilterGroup
            v-if="vendorList.length"
            v-model="vendorList"
            v-model:selected="selectedVendor"
            title="Vendor"
        />
    </div>
</template>
