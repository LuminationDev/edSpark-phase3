<script setup>
import {computed, onMounted} from "vue";
import {useRouter} from "vue-router";

import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import GeneratedQuoteDisplay from "@/js/components/catalogue/quote/GeneratedQuoteDisplay.vue";
import QuoteVendorAction from "@/js/components/quote/QuoteVendorAction.vue";
import QuoteVendorListing from "@/js/components/quote/QuoteVendorListing.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {guid} from "@/js/helpers/guidGenerator";
import {quoteService} from "@/js/service/quoteService";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({})

const emits = defineEmits([])
const router = useRouter()
const quoteStore = useQuoteStore()

onMounted(() => {
    quoteStore.initializeQuote()
    quoteStore.getUserGenQuote()
})
const onClearQuote = () => {
    quoteStore.clearQuote()
}


</script>


<template>
    <BaseLandingHero
        :title="LandingHeroText['quote']['title']"
        :title-paragraph="LandingHeroText['quote']['subtitle']"
        swoosh-color="teal"
    />
    <div
        class="mt-16 mx-10 quotePageOuterContainer"
    >
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
        <div class="col-span-10 flex justify-between flex-row otherRow">
            <GenericButton
                :callback="() => router.push('/catalogue')"
                type="teal"
            >
                Back to catalogue
            </GenericButton>
            <GenericButton
                :callback="onClearQuote"
                type="teal"
            >
                Clear quote
            </GenericButton>
        </div>
    </div>
    <div
        class="flex flex-col generatedQuoteContainer mt-10 mx-10"
    >
        <div class="font-medium mb-4 text-main-darkTeal text-xl">
            Generated quote
        </div>
        <GeneratedQuoteDisplay />
    </div>
    <!--    <GenericButton-->
    <!--        :callback="handlePrintQuote"-->
    <!--        type="teal"-->
    <!--    >-->
    <!--        invoke print-->
    <!--    </GenericButton>-->
</template>
