<script setup>
import {computed, ref} from 'vue'

import Accordion from "@/js/components/accordion/Accordion.vue";
import QuoteWideCard from "@/js/components/quote/QuoteWideCard.vue";
import ChevronDownIcon from "@/js/components/svg/chevron/ChevronDownIcon.vue";

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


</script>

<template>
    <div class="listingContainer w-full">
        <Accordion
            :title="quoteVendor"
        >
            <template
                v-for="(item,index) in quoteItems"
                :key="index"
            >
                <QuoteWideCard :item-data="item" />
            </template>
        </Accordion>
    </div>
</template>
