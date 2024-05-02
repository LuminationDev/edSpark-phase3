<script setup lang="ts">
import {computed, ref} from 'vue'

import CatalogueCardDescGenerator from "@/js/components/catalogue/CatalogueCardDescGenerator.vue";
import {catalogueImageURL} from "@/js/constants/serverUrl";
import {catalogueService} from "@/js/service/catalogueService";
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

const ComputerTypes = ['all-in-one', 'chromebook', 'desktop', 'notebook']
const DisplayTypes = ['monitor', 'tablet']
const {
    unique_reference,
    brand,
    type,
    name,
    image,
    price_inc_gst
} = props.catItem

const catCoverImageUrl = computed(() => {
    return catalogueImageURL + image
})

const catCardShortSpec = computed(() => {
    return catalogueService.getCatalogueShortSpecObj(props.catItem)
})

// image in card 270*200
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
                    {{ "$" + price_inc_gst }} <span
                        class="font-light text-base"
                    > inc. GST</span>
                </div>
                <div class="compareTickBox flex flex-row gap-2 ml-auto">
                    <input
                        type="checkbox"
                        class="p-2 rounded shadow"
                    >
                    <div class="compareLabel">
                        Compare
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

