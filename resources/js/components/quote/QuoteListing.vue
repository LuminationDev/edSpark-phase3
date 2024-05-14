<script setup>
import {computed, ref} from 'vue'

import QuoteWideCard from "@/js/components/quote/QuoteWideCard.vue";

const props = defineProps({
    quoteVendor: {
        type: String,
        required: true
    },
    quoteItems: {
        type: Array,
        required: true
    }
})

const isMinimised = ref(false)

const emits = defineEmits([])
</script>

<template>
    <div class="listingContainer w-full">
        <div class="mb-8 text-main-darkTeal text-xl">
            {{ quoteVendor }}
        </div>
        <div class="flex flex-col listingCardList">
            <template
                v-for="(item,index) in quoteItems"
                :key="index"
            >
                <QuoteWideCard :item-data="item" />
            </template>
        </div>
    </div>
</template>
