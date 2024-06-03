<script setup>
import {storeToRefs} from "pinia";
import {computed, ref} from 'vue'

import Accordion from "@/js/components/accordion/Accordion.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import QuotePdfRenderer from "@/js/components/catalogue/quote/QuotePdfRenderer.vue";
import QuoteWideCard from "@/js/components/quote/QuoteWideCard.vue";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {quoteService} from "@/js/service/quoteService";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({})

const emits = defineEmits([])

const quoteStore = useQuoteStore()
const {genQuote, quotePreview} = storeToRefs(quoteStore)

const getQuoteVendor = (quote) => {
    return quote?.quote_content[Object.keys(quote.quote_content)[0]]?.vendor
}

const getQuoteCreatedAt = (quote) => {
    return quote?.created_at
}

const onClickDownloadQuote = async (quote) => {
    console.log(quote)
    quotePreview.value = quote
    await new Promise((res, rej) => {
        setTimeout(() => {
            res()
        }, 500)
    })
    await quoteService.printQuote()
}


const renderPdfRenderer = computed(() => {
    return !!Object.keys(quotePreview).length
})

</script>

<template>
    <div class="GenQouteDisplayContainer">
        <div
            v-for="(quote,index) in genQuote"
            :key="index"
            class="border-[1px] border-slate-300 eachQuote flex flex-col mb-8 px-8 py-4 rounded-xl shadow"
        >
            <Accordion>
                <template #title>
                    <h2>{{ `Quote ID #${quote.id} - ${(getQuoteVendor(quote) ? getQuoteVendor(quote) : '')}` }}</h2>
                </template>
                <template #info>
                    {{ "Generated on " + formatDateToDayTime(getQuoteCreatedAt(quote)) }}
                </template>
                <template #content>
                    <div
                        v-for="(quoteItem, itemIdx) in quote.quote_content"
                        :key="itemIdx"
                    >
                        <QuoteWideCard
                            :item-data="quoteItem"
                            :display-only="true"
                        />
                    </div>
                </template>
            </Accordion>
            <div class="flex justify-between flex-row">
                <GenericButton
                    :callback=" () => onClickDownloadQuote(quote)"
                    type="teal"
                >
                    Download quote
                </GenericButton>
                <div class="font-semibold genQuoteTotal text-lg">
                    {{ `Total price (ex. GST): ${quote.total_price_ex_gst}` }}
                </div>
            </div>
        </div>
        <template v-if="renderPdfRenderer">
            <QuotePdfRenderer
                :quote="quotePreview"
            />
        </template>
    </div>
</template>
