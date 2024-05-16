<script setup lang="ts">
import {storeToRefs} from "pinia";
import {computed, onMounted, ref, watch} from 'vue'

import CatalogueAddToQuoteButton from "@/js/components/catalogue/CatalogueAddToQuoteButton.vue";
import CatalogueCardDescGenerator from "@/js/components/catalogue/CatalogueCardDescGenerator.vue";
import {catalogueService} from "@/js/service/catalogueService";
import {useCatalogueStore} from "@/js/stores/useCatalogueStore";
import {useQuoteStore} from "@/js/stores/useQuoteStore";
import {CatalogueItemType} from "@/js/types/catalogueTypes";

const props = defineProps({
    catItem: {
        type: {} as CatalogueItemType,
        required: true
    },
    clickCallback: {
        type: Function,
        required: true
    }
})

const emits = defineEmits(['addItemToCompareBasket'])

const catalogueStore = useCatalogueStore()
const {compareBasket} = storeToRefs(catalogueStore)

const quoteStore = useQuoteStore()


// handle adding item to compare list
const itemCompareStatus = ref(false)

watch(itemCompareStatus, () => {
    if (itemCompareStatus.value) {
        // compareBasket.value.push(props.catItem) // add if the compare becomes true
        catalogueStore.addItemToComparisonBasket(props.catItem)// add if the compare becomes true
    } else {// remove it the compare becomes false
        // compareBasket.value = compareBasket.value.filter(item => item.unique_reference !== props.catItem.unique_reference)
        catalogueStore.removeItemFromComparisonBasket(props.catItem.unique_reference)
    }
})

watch(compareBasket, () => {
    if (!compareBasket.value.some(item => item.unique_reference === props.catItem.unique_reference)) {
        itemCompareStatus.value = false
    }
})


const disableCompareButton = computed(() => {
    return compareBasket.value.length >= 3 && !itemCompareStatus.value
})

// onMounted, check if the compare basket has anything
onMounted(() => {
    if (compareBasket.value.length) {
        if (compareBasket.value.some(element => element.unique_reference == props.catItem.unique_reference)) {
            itemCompareStatus.value = true
        }
    }
})


const {
    unique_reference,
    brand,
    type,
    name,
    price_inc_gst,
    cover_image
} = props.catItem

const catCoverImageUrl = computed(() => {
    return catalogueService.getCatalogueCoverImage(cover_image);
})

const priceExtGst = computed(() => {
    return catalogueService.getExcGstPrice(price_inc_gst)
})

const catCardShortSpec = computed(() => {
    return catalogueService.getCatalogueShortSpecObj(props.catItem)
})



</script>

<template>
    <div
        class="catalogueCardOuter flex flex-col h-catH mb-6 w-catW"
    >
        <div
            class="
                border-[1px]
                border-slate-300
                catCardImageContainer
                cursor-pointer
                flex
                justify-center
                items-center
                h-[200px]
                mb-4
                p-4
                rounded-xl
                w-catW
                "
            @click="() => props.clickCallback(unique_reference)"
        >
            <img
                class="catalogueCoverImage h-full object-contain rounded"
                :src="catCoverImageUrl"
                alt=""
            >
        </div>
        <div class="cardBody flex flex-col h-[200px] w-full">
            <div
                class="cardBodyClickableWrapper cursor-pointer flex flex-col"
                @click="() => props.clickCallback(unique_reference)"
            >
                <div class="brandType mb-2 tag text-main-darkTeal">
                    {{ brand }} • {{ type }}
                </div>
                <div class="catItemName mb-2 text-xl">
                    {{ name }}
                </div>
                <div class="mb-4">
                    <CatalogueCardDescGenerator
                        :card-desc-obj="catCardShortSpec"
                    />
                </div>
            </div>
            <div class="flex flex-row mb-2 mt-auto priceAndCompareRow">
                <div class="price text-xl">
                    {{ "$" + priceExtGst }} <span
                        class="font-light text-base"
                    > ext. GST</span>
                </div>
                <div
                    class="compareTickBox flex flex-row gap-2 ml-auto"
                    :class="{'opacity-50 cursor-not-allowed' : disableCompareButton }"
                >
                    <input
                        v-model="itemCompareStatus"
                        type="checkbox"
                        class="p-2 rounded shadow"
                        :disabled="disableCompareButton"
                    >
                    <div class="compareLabel">
                        Compare
                    </div>
                </div>
            </div>
            <div class="addToQuoteRow flex w-full">
                <CatalogueAddToQuoteButton
                    :cat-item="props.catItem"
                />
            </div>
        </div>
    </div>
</template>

