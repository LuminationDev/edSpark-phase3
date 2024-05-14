<script setup>
import {useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import QuoteListing from "@/js/components/quote/QuoteListing.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({})

const emits = defineEmits([])
const router = useRouter()
const quoteStore = useQuoteStore()
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
    <div class="grid grid-cols-10 ml-8 mt-16 quotePageOuterContainer">
        <template v-for="(products, vendor) in quoteStore.getQuoteGroupedByVendor">
            <div class="col-span-8 grid listingColumn">
                <QuoteListing
                    :quote-vendor="vendor"
                    :quote-items="products"
                />
            </div>
            <div class="actionColumn col-span-2 grid">
                some action
            </div>
        </template>
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
