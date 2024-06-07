<script setup>

import QuoteVendorAction from "@/js/components/quote/QuoteVendorAction.vue";
import QuoteVendorListing from "@/js/components/quote/QuoteVendorListing.vue";

const props = defineProps({
    quoteVendor:{
        type: String,
        required: true
    },
    quoteItems :{
        type: Array,
        required: true
    }
})

</script>

<template>
    <div class="grid grid-cols-10 gap-4 mb-8">
        <div class="col-span-8 grid listingColumn">
            <QuoteVendorListing
                :quote-vendor="quoteVendor"
                :quote-items="quoteItems"
            />
        </div>
        <div class="actionColumn col-span-2 flex flex-col gap-4">
            <QuoteVendorAction
                :quote-vendor="quoteVendor"
                :quote-items="quoteItems"
            />
        </div>
    </div>
</template>
