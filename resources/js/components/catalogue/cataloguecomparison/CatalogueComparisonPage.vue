<script setup>
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from 'vue'
import {useRoute} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import CatalogueComparisonTable from "@/js/components/catalogue/cataloguecomparison/CatalogueComparisonTable.vue";
import ImageWithFallback from "@/js/components/global/ImageWithFallback.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import ChevronLeftNavIcon from "@/js/components/svg/ChevronLeftNavIcon.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {catalogueService} from "@/js/service/catalogueService";
import {useCatalogueStore} from "@/js/stores/useCatalogueStore";

const route = useRoute()
const catalogueStore = useCatalogueStore()
const {compareBasket} = storeToRefs(catalogueStore)
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
const catCoverImageUrl = (item) => {
    return catalogueService.getCatalogueCoverImage(item);
}

</script>

<template>
    <BaseLandingHero
        :title="LandingHeroText.comparison.title"
        :title-paragraph="LandingHeroText.comparison.subtitle"
    >
        <template #additionalText>
            <router-link
                to="/catalogue"
                class="flex items-end flex-row text-white underline"
            >
                <ChevronLeftNavIcon class="h-5 mb-[1px] w-5" />
                <span>Return to catalogue</span>
            </router-link>
        </template>
    </BaseLandingHero>
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
                <ImageWithFallback
                    class="h-[200px] mb-2 object-contain p-4 w-catW"
                    image-type="catalogue"
                    :image-url="catCoverImageUrl(getGroupedItemAttribute(item,'cover_image'))"
                    image-alt="item"
                />
                <div class="border-b-[1px] border-slate-300 catItemName mb-4 text-2xl">
                    {{ getGroupedItemAttribute(item, "name") }}
                </div>
            </div>
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
