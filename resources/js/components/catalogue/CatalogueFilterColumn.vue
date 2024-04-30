<script setup>

import CatalogueFilterGroup from "@/js/components/catalogue/CatalogueFilterGroup.vue";
import VueNoUiSlider from "@/js/components/slider/VueNoUiSlider.vue";
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

const priceSliderValues = [100,200,300,400,500,600,700,800,900];

const format = {
    to: function(value) {
        return priceSliderValues[Math.round(value)];
    },
    from: function (value) {
        return priceSliderValues.indexOf(Number(value));
    }
};
const priceSliderConfig = {
    start: [100, 200],
    range: { min: 0, max: priceSliderValues.length - 1 },
    step: 1,
    tooltips: true,
    format: format,
    // pips: { mode: 'steps', format: format },
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
        <VueNoUiSlider
            :values="priceSliderValues"
            :config="priceSliderConfig"
        />
        <CatalogueFilterGroup
            v-if="typeList.length"
            v-model="typeList"
            v-model:selected="selectedType"
            title="Type"
        />
        <CatalogueFilterGroup
            v-if="categoryList.length"
            v-model="categoryList"
            v-model:selected="selectedCategory"
            title="Category"
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
