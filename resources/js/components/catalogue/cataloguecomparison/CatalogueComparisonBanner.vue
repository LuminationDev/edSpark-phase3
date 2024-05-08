<script setup>


import {storeToRefs} from "pinia";
import {computed} from "vue";
import {useRouter} from "vue-router";

import GenericButton from "@/js/components/button/GenericButton.vue";
import CatalogueComparisonEmptySlot
    from "@/js/components/catalogue/cataloguecomparison/CatalogueComparisonEmptySlot.vue";
import Close from "@/js/components/svg/Close.vue";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {useCatalogueStore} from "@/js/stores/useCatalogueStore";

const catalogueStore = useCatalogueStore()
const {compareBasket} = storeToRefs(catalogueStore)

const router = useRouter()

const handleRemoveFromCompareBasket = (itemUniqueRef) => {
    catalogueStore.removeItemFromComparisonBasket(itemUniqueRef);
}

const disableCompareButton = computed(() => {
    return compareBasket.value.length < 2
})

const handleClickCompare = () => {
    const skusList = compareBasket.value.map(item => item.unique_reference)
    router.push({name: 'compare-item', query: {sku: skusList}})
}
const handleClickClearCompare= () =>{
    catalogueStore.clearComparisonBasket()
}
</script>

<template>
    <div
        v-if="catalogueStore.showCompareBanner"
        class="
            bg-slate-200
            border-[1px]
            border-slate-300
            fixed
            bottom-0
            left-0
            flex
            justify-center
            items-center
            h-28
            rounded-b-lg
            shadow
            w-full
            gap-6
            "
    >
        <div
            v-for="(item,index) in compareBasket"
            :key="index"
            class="border-2 border-slate-300 flex justify-center items-center h-20 relative w-20"
        >
            <div class="absolute -top-2 -right-2 item-remove-button text-xl">
                <Close
                    class="bg-slate-600 border-2 border-slate-600 cursor-pointer fill-white h-4 rounded-full w-4"
                    @click="() =>handleRemoveFromCompareBasket(item.unique_reference)"
                />
            </div>
            <img
                class="max-h-full"
                :src="catalogueImageURL + item.image"
                alt="''"
            >
        </div>

        <CatalogueComparisonEmptySlot
            v-for="(number,index) in (3 - catalogueStore.getCompareBasketLength) "
            :key="index"
        />
        <GenericButton
            :callback="handleClickCompare"
            type="teal"
            :disabled="disableCompareButton"
        >
            Compare
        </GenericButton>
        <GenericButton
            :callback="handleClickClearCompare"
            type="plain"
        >
            Clear
        </GenericButton>
    </div>
</template>
