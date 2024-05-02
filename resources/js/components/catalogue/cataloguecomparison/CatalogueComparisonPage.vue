<script setup>
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from 'vue'
import {useRoute} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {catalogueService} from "@/js/service/catalogueService";
import {useCatalogueStore} from "@/js/stores/useCatalogueStore";

const route = useRoute()
const catalogueStore = useCatalogueStore()
const {compareBasket} = storeToRefs(catalogueStore)
console.log('welcoem to comparison page')
console.log(route.query.sku)
const skusList= ref(route.query.sku)
const isLoading = ref(true)

onMounted(() =>{
    // perform checking if the sku in url are inside storage
    // filter out what is not inside the storage and fetch the missing
    console.log(skusList.value)
    const missingDataReference = []
    skusList.value.forEach(sku => {
        if(!compareBasket.value.some(item => item.unique_reference === sku)){
            missingDataReference.push(sku)
        }
    })
    if(missingDataReference.length){// means has missing
        console.log('has missing item')
        // remove unwanted items from basket
        compareBasket.value.filter(item => skusList.value.includes(item.unique_reference))
        // fetch item data
        missingDataReference.forEach(data =>{
            catalogueService.fetchSingleProductByReference(data).then(res => {
                console.log(res.data.data)
                catalogueStore.addItemToComparisonBasket(res.data.data)
                isLoading.value = false
            })
        })
    } else{
        console.log('no missing')
        isLoading.value = false
    }
})
const groupedData = computed(() =>{
    if(isLoading.value)return []
    else{
        return compareBasket.value.map(item => catalogueService.getGroupedItemData(item))
    }
})
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
                v-for="(item,index) in compareBasket"
                :key="index"
                class="flex flex-col place-items-center"
            >
                <img
                    :src="catalogueImageURL + item.image"
                    class="h-24 w-24"
                >
                <div class="border-b-[1px] border-slate-300 catItemName mb-4 text-2xl">
                    {{ item.name }}
                </div>
            </div>
            <div />
            <div
                v-for="(eachItem, index) in groupedData"
                :key="index"
            >
                <div
                    v-for="(row, index) in eachItem"
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
        </div>
    </div>
    <div v-else>
        <Loader
            loader-type="page"
            loader-message="Fetching product"
        />
    </div>
</template>
