<script setup lang="ts">
import {computed, onMounted, Ref, ref, toRefs} from 'vue'
import {RouteParamValue, useRoute} from "vue-router";

import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import useIsLoading from "@/js/composables/useIsLoading";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {catalogueService} from "@/js/service/catalogueService";
import {CatalogueItemType} from "@/js/types/catalogueTypes";

const props = defineProps({})


const route = useRoute()
const uniqueRef: string | RouteParamValue[] = route.params.ref
const isLoading = ref(false)
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
            group: 'overview'
        },
        {
            name: 'vendor',
            value: itemData.value.vendor,
            display_text: "Vendor",
            group: 'availability'
        },
        {
            name: 'warranty',
            value: itemData.value.warranty,
            display_text: "Warranty",
            group: 'availability'
        },
        {
            name: 'price_inc_gst',
            value: itemData.value.price_inc_gst,
            display_text: "Price (incl. GST)",
            group: 'availability'
        },
        {
            name: 'price_expiry',
            value: itemData.value.price_expiry,
            display_text: "Price Expiry",
            group: 'availability'
        },
        {
            name: 'available_now',
            value: itemData.value.available_now,
            display_text: "Available Now",
            group: 'availability'
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
            group: 'hardware'
        },
        {
            name: 'wireless',
            value: itemData.value.wireless,
            display_text: "Wireless",
            group: 'hardware'
        },
        {
            name: 'webcam',
            value: itemData.value.webcam,
            display_text: "Webcam",
            group: 'hardware'
        },
        {
            name: 'form_factor',
            value: itemData.value.form_factor,
            display_text: "Form Factor",
            group: 'hardware'
        },
        {
            name: 'stylus',
            value: itemData.value.stylus,
            display_text: "Stylus",
            group: 'hardware'
        },
        {
            name: 'id',
            value: itemData.value.id,
            display_text: "ID",
            group: 'more_info'
        },
        {
            name: 'unique_reference',
            value: itemData.value.unique_reference,
            display_text: "Unique Reference",
            group: 'more_info'
        },
        {
            name: 'name',
            value: itemData.value.name,
            display_text: "Name",
            group: 'more_info'
        },
        {
            name: 'image',
            value: itemData.value.image,
            display_text: "Image",
            group: 'more_info'
        },
        {
            name: 'product_number',
            value: itemData.value.product_number,
            display_text: "Product Number",
            group: 'more_info'
        },
        {
            name: 'other',
            value: itemData.value.other,
            display_text: "Other",
            group: 'more_info'
        },
        {
            name: 'corporate',
            value: itemData.value.corporate,
            display_text: "Corporate",
            group: 'more_info'
        },
        {
            name: 'administration',
            value: itemData.value.administration,
            display_text: "Administration",
            group: 'more_info'
        },
        {
            name: 'curriculum',
            value: itemData.value.curriculum,
            display_text: "Curriculum",
            group: 'more_info'
        }
    ]
});

const tableHeaders = ['overview', 'specs', 'hardware', 'availability', 'more_info']

</script>

<template>
    <template v-if="isLoading">
        <Loader
            loader-type="page"
            loader-message="Fetching product"
        />
    </template>
    <template v-else>
        <BaseHero
            :background-url="itemData.image"
            :background-server-url="catalogueImageURL"
        >
            <!--            <template #titleText>-->
            <!--                {{ itemData.name }}-->
            <!--            </template>-->
            <!--            <template #hardwareProvider>-->
            <!--                {{ itemData.vendor }}-->
            <!--            </template>-->
            <!--            <template #subtitleText2>-->
            <!--                Brand: {{ itemData.brand }}-->
            <!--            </template>-->
        </BaseHero>
        <div class="CatalogueSingleOuter mt-16 mx-8">
            <template
                v-for="(header,index) in tableHeaders"
                :key="index"
            >
                <div class="border-[1px] border-slate-300 flex flex-col mb-4 rounded-lg tableOuter w-1/2">
                    <div class="capitalize px-4 py-2 text-lg text-main-teal">
                        {{ header.replace(/_/g, " ") }}
                    </div>
                    <div
                        v-for="(row, index) in structuredCatItemData.filter(item => item.group === header)"
                        :key="`${index}-row`"
                        class="px-4 py-2 even:bg-main-teal/5"
                    >
                        <div class="grid grid-cols-2 w-full">
                            <div>
                                {{ row.display_text }}
                            </div>
                            <div>{{ row.value }}</div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>
</template>
<style scoped>
table {
    width: 50%;
    border-collapse: collapse;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid teal;
}
</style>