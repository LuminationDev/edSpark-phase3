<script setup>

import GenericButton from "@/js/components/button/GenericButton.vue";
import CatalogueFilterGroup from "@/js/components/catalogue/CatalogueFilterGroup.vue";
import CataloguePerPageSelector from "@/js/components/catalogue/CataloguePerPageSelector.vue";
import CataloguePriceSlider from "@/js/components/catalogue/CataloguePriceSlider.vue";
import Loader from "@/js/components/spinner/Loader.vue";

const props = defineProps({
    isFilterLoading: {
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
const priceRange = defineModel('priceRange')
const perPage = defineModel('perPage')

const emits = defineEmits(['priceChanged'])

const onReceivePriceChanged = () =>{
    emits('priceChanged')
}

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
        class="flex flex-col"
    >
        <router-link to="/catalogue/quote">
            <GenericButton
                :callback="() => {}"
                type="teal"
            >
                View Quotes
            </GenericButton>
        </router-link>

        <CatalogueFilterGroup
            v-if="typeList.length"
            v-model="typeList"
            v-model:selected="selectedType"
            title="Type"
            :default-show-filter="true"
        />
        <CatalogueFilterGroup
            v-if="brandList.length"
            v-model="brandList"
            v-model:selected="selectedBrand"
            title="Brand"
            :default-show-filter="true"
        />
        <CatalogueFilterGroup
            v-if="categoryList.length"
            v-model="categoryList"
            v-model:selected="selectedCategory"
            title="Category"
            :default-show-filter="true"
        />

        <CatalogueFilterGroup
            v-if="vendorList.length"
            v-model="vendorList"
            v-model:selected="selectedVendor"
            title="Vendor"
            :default-show-filter="true"
        />
        <CataloguePriceSlider
            v-model:price-range="priceRange"
            @price-changed="onReceivePriceChanged"
        />
        <CataloguePerPageSelector v-model="perPage" />
    </div>
</template>
