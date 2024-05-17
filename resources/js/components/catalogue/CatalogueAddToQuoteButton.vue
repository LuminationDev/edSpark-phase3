<script setup>
import {computed, ref} from 'vue'

import GenericButton from "@/js/components/button/GenericButton.vue";
import CatalogueCartIcon from "@/js/components/svg/catalogue/CatalogueCartIcon.vue";
import {useQuoteStore} from "@/js/stores/useQuoteStore";

const props = defineProps({
    callback: {
        type: Function,
        required: false,
        default: () => {
        }
    },
    catItem: {
        type: String,
        required: false,
        default: ""
    }
})
const quoteStore = useQuoteStore()
const showConfirmation = ref(false)

// should be on the card
const itemAdded = computed(() => {
    return quoteStore.isItemInQuote(props.catItem.unique_reference)
})

const quantityInQuote = computed(() => {
    const quotes = quoteStore.getQuote
    const itemInQuote = quotes.filter(item => item.unique_reference === props.catItem.unique_reference)
    if(itemInQuote.length){
        return itemInQuote[0].quantity
    } else return 0
})

const onClickAddToQuote = () =>{
    quoteStore.addToQuote(props.catItem)
    showConfirmation.value = true
    setTimeout(() => {
        showConfirmation.value =false
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
            >
                <CatalogueCartIcon />
                <span class="ml-4" />Add to quote
            </GenericButton>
        </template>
        <template v-else>
            <GenericButton
                class="!text-base py-3 hover:!bg-secondary-cherry"
                :callback="props.callback"
                type="red"
            >
                <span class="ml-4" />ADDED! {{ `(${quantityInQuote})` }}
            </GenericButton>
        </template>
    </div>
</template>