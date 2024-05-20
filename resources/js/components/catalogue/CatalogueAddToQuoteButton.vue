<script setup>
import {storeToRefs} from "pinia";
import {computed, ref} from 'vue'

import GenericButton from "@/js/components/button/GenericButton.vue";
import CatalogueCartIcon from "@/js/components/svg/catalogue/CatalogueCartIcon.vue";
import {quoteService} from "@/js/service/quoteService";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({
    callback: {
        type: Function,
        required: false,
        default: () => {
        }
    },
    catItem: {
        type: Object,
        required: false,
        default: ""
    }
})
const quoteStore = useQuoteStore()
const {quote,quoteLoading} = storeToRefs(quoteStore)
const showConfirmation = ref(false)

// should be on the card
const itemIsInQuote = computed(() => {
    return quoteStore.isItemInQuote(props.catItem.unique_reference)
})

const quantityInQuote = computed(() => {
    const quotes = quoteStore.getQuote
    const itemInQuote = quotes.filter(item => item.unique_reference === props.catItem.unique_reference)
    if (itemInQuote.length) {
        return itemInQuote[0].quantity
    } else return 0
})

const onClickAddToQuote = () => {
    const oldQuote = quoteStore.getQuote
    if (itemIsInQuote.value) {
        //send update
        // try catch to revert incase network fails
        try {
            quoteService.updateItemQuantityInCart(props.catItem.unique_reference, quantityInQuote.value + 1)
        } catch (err) {
            quote.value = oldQuote
            console.log('Failed to update item, reverted to older value')
        }
    } else {
        //post to add here
        try {
            quoteService.addItemToCart(props.catItem.unique_reference, 1)
        } catch (err) {
            quote.value = oldQuote
            console.log('Failed to update item, reverted to older value')
        }

    }
    quoteStore.addToQuote(props.catItem)
    showConfirmation.value = true
    setTimeout(() => {
        showConfirmation.value = false
    }, 1000)


}
</script>

<template>
    <div class="flex flex-row gap-2">
        <template v-if="!showConfirmation">
            <GenericButton
                class="!text-base py-3"
                :callback="onClickAddToQuote"
                type="teal"
                :class="{'disabled ':quoteLoading}"
            >
                <CatalogueCartIcon />
                <span class="ml-4" />Add to quote {{ quantityInQuote ? `(${quantityInQuote})` : '' }}
            </GenericButton>
        </template>
        <template v-else>
            <GenericButton
                class="!text-base py-3 hover:!bg-secondary-cherry"
                :callback="props.callback"
                type="red"
            >
                <span class="ml-4" />Added to quote! {{ `(${quantityInQuote})` }}
            </GenericButton>
        </template>
    </div>
</template>