<script setup>
import {onMounted} from "vue";
import {useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import QuoteVendorAction from "@/js/components/quote/QuoteVendorAction.vue";
import QuoteVendorListing from "@/js/components/quote/QuoteVendorListing.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {guid} from "@/js/helpers/guidGenerator";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({})

const emits = defineEmits([])
const router = useRouter()
const quoteStore = useQuoteStore()

onMounted(() => {
    quoteStore.initializeQuote()
})
const handleClearQuote = () => {
    quoteStore.clearQuote()
}

</script>


<template>
    <BaseLandingHero
        :title="LandingHeroText['quote']['title']"
        :title-paragraph="LandingHeroText['quote']['subtitle']"
        swoosh-color="teal"
    />
    <div class="ml-8 mt-16 quotePageOuterContainer">
        <div
            v-for="(products, vendor) in quoteStore.getQuoteGroupedByVendor"
            :key="vendor + guid()"
            class="grid grid-cols-10 gap-4 mb-8"
        >
            <div class="col-span-8 grid listingColumn">
                <QuoteVendorListing
                    :quote-vendor="vendor"
                    :quote-items="products"
                />
            </div>
            <div class="actionColumn col-span-2 grid">
                <QuoteVendorAction
                    :quote-vendor="vendor"
                    :quote-items="products"
                />
            </div>
        </div>
        <div class="col-span-10 otherRow">
            <GenericButton
                :callback="handleClearQuote"
                type="teal"
            >
                Clear quote
            </GenericButton>
            <GenericButton
                :callback="() => router.push('/catalogue')"
                type="teal"
            >
                back to catalogue
            </GenericButton>
        </div>
    </div>
</template>
