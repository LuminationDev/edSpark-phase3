<script setup lang="ts">
import {computed, onMounted, Ref, ref,} from 'vue'
import {RouteParamValue, useRoute} from "vue-router";

import CatalogueAddToQuoteButton from "@/js/components/catalogue/CatalogueAddToQuoteButton.vue";
import CataloguePriceDisplay from "@/js/components/catalogue/CataloguePriceDisplay.vue";
import CatalogueSingleShortSpec from "@/js/components/catalogue/cataloguesingle/CatalogueSingleShortSpec.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {catalogueService} from "@/js/service/catalogueService";
import {CatalogueItemType, catalogueSingleHeaders} from "@/js/types/catalogueTypes";


const route = useRoute()
const uniqueRef: string | RouteParamValue[] = route.params.ref
const isLoading = ref(true)
const itemData: Ref<CatalogueItemType> = ref({})

onMounted(() => {
    isLoading.value = true
    catalogueService.fetchSingleProductByReference(uniqueRef).then(res => {
        console.log(res.data.data)
        itemData.value = res.data.data

    }).finally(() => {
        isLoading.value = false
    })
})


const structuredCatItemData = computed(() => {
    return [
        {
            name: 'brand',
            value: itemData.value.brand,
            display_text: "Brand",
            group: 'overview'
        },
        {
            name: 'type',
            value: itemData.value.type,
            display_text: "Type",
            group: 'overview'
        },
        {
            name: 'category',
            value: itemData.value.category,
            display_text: "Category",
            group: 'hidden'
        },
        {
            name: 'vendor',
            value: itemData.value.vendor,
            display_text: "Vendor",
            group: 'overview'
        },
        {
            name: 'warranty',
            value: itemData.value.warranty,
            display_text: "Warranty",
            group: 'overview'
        },
        {
            name: 'price_inc_gst',
            value: itemData.value.price_inc_gst,
            display_text: "Price (incl. GST)",
            group: 'hidden'
        },
        {
            name: 'price_expiry',
            value: itemData.value.price_expiry,
            display_text: "Price Expiry",
            group: 'overview'
        },
        {
            name: 'available_now',
            value: itemData.value.available_now,
            display_text: "Available Now",
            group: 'hidden'
        },
        {
            name: 'storage',
            value: itemData.value.storage,
            display_text: "Storage",
            group: 'specs'
        },
        {
            name: 'memory',
            value: itemData.value.memory,
            display_text: "Memory",
            group: 'specs'
        },
        {
            name: 'battery_life',
            value: itemData.value.battery_life,
            display_text: "Battery Life",
            group: 'specs'
        },
        {
            name: 'processor',
            value: itemData.value.processor,
            display_text: "Processor",
            group: 'specs'
        },
        {
            name: 'display',
            value: itemData.value.display,
            display_text: "Display",
            group: 'specs'
        },
        {
            name: 'graphics',
            value: itemData.value.graphics,
            display_text: "Graphics",
            group: 'specs'
        },
        {
            name: 'operating_system',
            value: itemData.value.operating_system,
            display_text: "Operating System",
            group: 'specs'
        },
        {
            name: 'weight',
            value: itemData.value.weight,
            display_text: "Weight",
            group: 'specs'
        },
        {
            name: 'wireless',
            value: itemData.value.wireless,
            display_text: "Wireless",
            group: 'specs'
        },
        {
            name: 'webcam',
            value: itemData.value.webcam,
            display_text: "Webcam",
            group: 'specs'
        },
        {
            name: 'form_factor',
            value: itemData.value.form_factor,
            display_text: "Form Factor",
            group: 'specs'
        },
        {
            name: 'stylus',
            value: itemData.value.stylus,
            display_text: "Stylus",
            group: 'specs'
        },
        {
            name: 'id',
            value: itemData.value.id,
            display_text: "ID",
            group: 'hidden'
        },
        {
            name: 'unique_reference',
            value: itemData.value.unique_reference,
            display_text: "Unique Reference",
            group: 'hidden'
        },
        {
            name: 'name',
            value: itemData.value.name,
            display_text: "Name",
            group: 'hidden'
        },
        {
            name: 'image',
            value: itemData.value.image,
            display_text: "Image",
            group: 'hidden'
        },
        {
            name: 'product_number',
            value: itemData.value.product_number,
            display_text: "Product Number",
            group: 'hidden'
        },
        {
            name: 'other',
            value: itemData.value.other,
            display_text: "Other",
            group: 'specs'
        },
        {
            name: 'corporate',
            value: itemData.value.corporate,
            display_text: "Corporate",
            group: 'hidden'
        },
        {
            name: 'administration',
            value: itemData.value.administration,
            display_text: "Administration",
            group: 'hidden'
        },
        {
            name: 'curriculum',
            value: itemData.value.curriculum,
            display_text: "Curriculum",
            group: 'hidden'
        }
    ]
});


const catCoverImageUrl = computed(() => {
    return catalogueService.getCatalogueCoverImage(itemData.value.cover_image);
})

const catItemShortSpec = computed(() => {
    if (itemData.value.type) {
        return catalogueService.getCatalogueShortSpecObj(itemData.value)

    } else {
        return {}
    }
})

const shortSpecEntries = computed(() => {
    return Object.entries(catItemShortSpec.value)
})


</script>

<template>
    <template v-if="isLoading && itemData">
        <Loader
            loader-type="page"
            loader-message="Fetching product"
        />
    </template>
    <template v-else>
        <div class="flex flex-row gap-16 mt-24 px-8 w-full">
            <div
                class="
                    ImageDisplayContainer
                    border-[1px]
                    border-slate-300
                    flex
                    justify-center
                    items-center
                    max-h-[400px]
                    rounded-lg
                    w-1/2
                    "
            >
                <img
                    class="h-full max-h-[400px] max-w-full"
                    :src="catCoverImageUrl"
                    alt="image"
                >
            </div>
            <div class="InfoDisplayContainter flex flex-col w-1/2">
                <div class="flex flex-row gap-2 mb-4 text-main-teal">
                    <router-link
                        to="/dashboard"
                        class="hover:text-main-darkTeal"
                    >
                        {{ `Home /` + " " }}
                    </router-link>
                    <router-link
                        to="/catalogue"
                        class="hover:text-main-darkTeal"
                    >
                        {{ `Catalogue /` + " " }}
                    </router-link>
                    <div class="text-black">
                        {{ itemData.name }}
                    </div>
                </div>
                <div class="brandType mb-4 tag text-main-darkTeal">
                    {{ itemData.brand }} • {{ itemData.type }}
                </div>
                <div class="border-b-[1px] border-slate-300 catItemName mb-4 text-2xl">
                    {{ itemData.name }}
                </div>
                <CatalogueSingleShortSpec
                    class="mb-4"
                    :short-spec-entries="shortSpecEntries"
                />
                <CataloguePriceDisplay
                    class="mb-4"

                    :price-value="+itemData.price_inc_gst"
                    :include-gst="true"
                />
                <CatalogueAddToQuoteButton
                    :cat-item="itemData"
                />
            </div>
        </div>
        <div class="CatalogueSingleOuter grid grid-cols-2 gap-4 mt-16 mx-8">
            <template
                v-for="(header,index) in catalogueSingleHeaders"
                :key="index"
            >
                <!--                turning this into component and have the props hidable-->
                <div class="border-[1px] border-slate-300 flex flex-col h-fit mb-4 rounded-lg tableOuter w-full">
                    <div class="capitalize px-4 py-2 text-lg text-main-darkTeal">
                        {{ header.replace(/_/g, " ") }}
                    </div>
                    <div
                        v-for="(row, index) in structuredCatItemData.filter(item => item.group === header)"
                        :key="`${index}-row`"
                        class="px-4 py-2 even:bg-main-teal/5"
                    >
                        <div class="grid grid-cols-2 py-1 w-full">
                            <div>
                                {{ row.display_text }}
                            </div>
                            <div class="text-center text-slate-600">
                                {{ row.value }}
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
</template>
<style scoped>
</style>