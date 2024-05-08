<script setup>
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from 'vue'
import {useRoute} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import CatalogueComparisonTable from "@/js/components/catalogue/cataloguecomparison/CatalogueComparisonTable.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {catalogueService} from "@/js/service/catalogueService";
import {useCatalogueStore} from "@/js/stores/useCatalogueStore";

const route = useRoute()
const catalogueStore = useCatalogueStore()
const {compareBasket} = storeToRefs(catalogueStore)
console.log('welcoem to comparison page')
console.log(route.query.sku)
const skusList = ref(route.query.sku)
const isLoading = ref(true)

onMounted(() => {
    // perform checking if the sku in url are inside storage
    // filter out what is not inside the storage and fetch the missing
    const missingDataReference = []
    if (skusList.value) {
        skusList.value.forEach(sku => {
            if (!compareBasket.value.some(item => item.unique_reference === sku)) {
                missingDataReference.push(sku)
            }
        })
        if (missingDataReference.length || compareBasket.value.length > 3) {// means has missing and or more than 3 items
            console.log('has missing item')
            // remove unwanted items from basket
            compareBasket.value = compareBasket.value.filter(item => skusList.value.includes(item.unique_reference))
            // fetch item data
            missingDataReference.forEach(data => {
                catalogueService.fetchSingleProductByReference(data).then(res => {
                    console.log(res.data.data)
                    catalogueStore.addItemToComparisonBasket(res.data.data)
                    isLoading.value = false
                })
            })
        } else {
            console.log('no missing')
            isLoading.value = false
        }
    } else {
        isLoading.value = false
    }
})

const groupedPaddedData = computed(() => {
    if (isLoading.value) return []
    else {
        const groupedData = compareBasket.value.map(item => catalogueService.getGroupedItemData(item))
        if (groupedData.length < 3) {
            const paddingCount = 3 - groupedData.length
            for (let x = 0; x < paddingCount; x++) {
                groupedData.push([{}])
            }
            return groupedData;
        } else {
            return groupedData
        }
    }
})

const getGroupedItemAttribute = (item, attributeName) => {
    const result = item.filter(attribute => attribute.name === attributeName)[0]
    if (result && result.value) {
        return result.value
    } else {
        return ""
    }
}


</script>

<template>
    <BaseLandingHero
        title="Compare hardware"
        title-paragraph="Compare hardware"
    />
    <div
        v-if="!isLoading"
        class="comparisonPageOuter mt-10"
    >
        <div class="grid grid-cols-4">
            <div class="emptySpace" />
            <div
                v-for="(item,index) in groupedPaddedData"
                :key="index"
                class="flex flex-col place-items-center"
            >
                <img
                    v-if="getGroupedItemAttribute(item,'image')"
                    :src="catalogueImageURL + getGroupedItemAttribute(item,'image')"
                    class="h-24 w-24"
                    alt="icon"
                >
                <div class="border-b-[1px] border-slate-300 catItemName mb-4 text-2xl">
                    {{ getGroupedItemAttribute(item, "name") }}
                </div>
            </div>
            <!--            <div class="comparisonTitles">-->
            <!--                <div-->
            <!--                    v-for="(row, index) in groupedPaddedData[0]"-->
            <!--                    :key="`${index}-title-row`"-->
            <!--                    class="px-4 py-2 even:bg-main-teal/5"-->
            <!--                >-->
            <!--                    <div class="grid place-items-center h-8 w-full">-->
            <!--                        <div class="text-center text-slate-600">-->
            <!--                            {{ row.display_text }}-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div-->
            <!--                v-for="(eachItem, index) in groupedPaddedData"-->
            <!--                :key="index"-->
            <!--                class="compareSpecColumn"-->
            <!--            >-->
            <!--                <div-->
            <!--                    v-for="(row, index) in eachItem"-->
            <!--                    :key="`${index}-row`"-->
            <!--                    class="px-4 py-2 even:bg-main-teal/5"-->
            <!--                >-->
            <!--                    <div class="grid place-items-center h-8 w-full">-->
            <!--                        <div class="text-center text-slate-600">-->
            <!--                            {{ row.value || '' }}-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
        </div>
        <CatalogueComparisonTable :data="groupedPaddedData" />
    </div>

    <div v-else>
        <Loader
            loader-type="page"
            loader-message="Fetching product"
        />
    </div>
</template>
