<script setup>
import {storeToRefs} from "pinia";
import {computed, ref} from 'vue'

import GenericButton from "@/js/components/button/GenericButton.vue";
import QuoteWideCard from "@/js/components/quote/QuoteWideCard.vue";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({})

const emits = defineEmits([])

const quoteStore = useQuoteStore()
const {genQuote} = storeToRefs(quoteStore)
</script>

<template>
    <div class="GenQouteDisplayContainer">
        <div
            v-for="(quote,index) in genQuote"
            :key="index"
            class="border-[1px] border-slate-300 eachQuote flex flex-col px-8 py-4 rounded-xl shadow"
        >
            <span class="font-semibold my-4 text-xl">
                {{ `Quote ID #${quote.id}` }}

            </span>
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
        </div>
    </div>
</template>
