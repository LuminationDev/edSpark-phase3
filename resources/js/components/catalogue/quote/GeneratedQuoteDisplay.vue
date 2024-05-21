<script setup>
import {storeToRefs} from "pinia";
import {computed, ref} from 'vue'

import Accordion from "@/js/components/accordion/Accordion.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import QuoteWideCard from "@/js/components/quote/QuoteWideCard.vue";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({})

const emits = defineEmits([])

const quoteStore = useQuoteStore()
const {genQuote} = storeToRefs(quoteStore)

const quoteVendor = computed(() => {
    const vendor = genQuote.value[0]?.quote_content[0]?.vendor
    if (vendor) return vendor
    else return ''
})

const getQuoteVendor = (quote) => {
    console.log(quote)
    console.log(quote.quote_content)
    return quote?.quote_content[Object.keys(quote.quote_content)[0]]?.vendor
}
</script>

<template>
    <div class="GenQouteDisplayContainer">
        <div
            v-for="(quote,index) in genQuote"
            :key="index"
            class="border-[1px] border-slate-300 eachQuote flex flex-col mb-8 px-8 py-4 rounded-xl shadow"
        >
            <Accordion
                :key="index"
                :title="`Quote ID #${quote.id} - ${ getQuoteVendor(quote) ?getQuoteVendor(quote):''}` "
                title-classes=""
            >
                <div
                    v-for="(quoteItem, itemIdx) in quote.quote_content"
                    :key="itemIdx"
                >
                    <QuoteWideCard
                        :item-data="quoteItem"
                        :display-only="true"
                    />
                </div>
                <div class="font-semibold genQuoteTotal ml-auto text-xl">
                    {{ `Total price (ex. GST): ${quote.total_price_ex_gst}` }}
                </div>
                <div>
                    <GenericButton
                        :callback="() => {}"
                        type="teal"
                    >
                        Download quote
                    </GenericButton>
                </div>
            </Accordion>
        </div>
    </div>
</template>
