<script setup>

import {storeToRefs} from "pinia";
import {computed} from "vue";

import GenericButton from "@/js/components/button/GenericButton.vue";
import CatalogueComparisonEmptySlot
    from "@/js/components/catalogue/cataloguecomparison/CatalogueComparisonEmptySlot.vue";
import {useCatalogueStore} from "@/js/stores/useCatalogueStore";
const catalogueStore = useCatalogueStore()
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
        <CatalogueComparisonEmptySlot />
        <GenericButton
            :callback="()=>{}"
            type="teal"
        >
            Compare
        </GenericButton>
        <GenericButton
            :callback="()=>{}"
            type="plain"
        >
            Clear
        </GenericButton>
    </div>
</template>
