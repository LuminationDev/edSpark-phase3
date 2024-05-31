<script setup>
import {computed, onMounted} from "vue";
import {useRouter} from "vue-router";

import Accordion from "@/js/components/accordion/Accordion.vue";
import BaseLandingHero from "@/js/components/bases/BaseLandingHero.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import GeneratedQuoteDisplay from "@/js/components/catalogue/quote/GeneratedQuoteDisplay.vue";
import QuoteVendorAction from "@/js/components/quote/QuoteVendorAction.vue";
import QuoteVendorGroup from "@/js/components/quote/QuoteVendorGroup.vue";
import QuoteVendorListing from "@/js/components/quote/QuoteVendorListing.vue";
import QuoteWideCard from "@/js/components/quote/QuoteWideCard.vue";
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
const showEmptyQuotePage = computed(() =>{
    return !!Object.keys(quoteStore.getQuoteGroupedByVendor).length;
})

</script>


<template>
    <BaseLandingHero
        :title="LandingHeroText['quote']['title']"
        :title-paragraph="LandingHeroText['quote']['subtitle']"
        swoosh-color="teal"
    />
    <div
        class="mb-4 mt-16 mx-10 quotePageOuterContainer"
    >
        <template v-if="showEmptyQuotePage">
            <div
                v-for="(products, vendor) in quoteStore.getQuoteGroupedByVendor"
                :key="vendor + guid()"
            >
                <QuoteVendorGroup
                    :quote-items="products"
                    :quote-vendor="vendor"
                />
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
                    Cancel all quotes
                </GenericButton>
            </div>
        </template>
        <template v-else>
            <div class="flex items-center flex-col">
                <div class="flex min-h-24 text-main-darkTeal text-xl">
                    Quote is current empty. Please add items from the catalogue.<br>
                    Or, browse the quotes you've generated previously below
                </div>
                <GenericButton
                    :callback="() => router.push('/catalogue')"
                    type="teal"
                >
                    Back to catalogue
                </GenericButton>
            </div>
        </template>
    </div>
    <hr>
    <div
        class="flex flex-col generatedQuoteContainer mt-10 mx-10"
    >
        <Accordion :default-open="false">
            <template #title>
                <div class="font-medium mb-4 text-main-darkTeal text-xl">
                    Generated quote
                </div>
            </template>
            <template #content>
                <GeneratedQuoteDisplay />
            </template>
        </Accordion>
    </div>
</template>
