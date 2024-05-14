import {useStorage} from "@vueuse/core";
import {defineStore} from "pinia";

import {CatalogueFilterField, CatalogueItemType} from "@/js/types/catalogueTypes";

export const useQuoteStore = defineStore('quote', {
    state: () => ({
        quote: useStorage('EDSPARK_CATALOGUE_QUOTE', [], localStorage, {mergeDefaults: true}),
        qouteCreationDate: useStorage('EDSPARK_QUOTE_CREATION_DATE', 0, localStorage),
        qouteUpdateDate: useStorage('EDSPARK_QUOTE_UPDATE_DATE', 0, localStorage),
        quoteLoading: false

    }),
    getters: {
        getQuote() {
            return this.quote
        },
        getQuoteGroupedByVendor: (state) =>{
            return state.quote.reduce((acc, product) => {
                const vendor = product.vendor;
                if (!acc[vendor]) {
                    acc[vendor] = [];
                }
                acc[vendor].push(product);
                return acc;
            }, {});
        }

    },
    actions: {
        addToQuote(item: CatalogueItemType) {
            const existingItem = this.quote.find(currentItem => currentItem.unique_reference === item.unique_reference);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                this.quote.push({...item, quantity: 1});
            }
        },
        removeFromQuote(itemReference) {
            this.quote = this.quote.filter(item => item.unique_reference !== itemReference);
        },
        changeQuantity(item, newQuantity) {
            const quoteItem = this.quote.find(quoteItem => quoteItem.unique_reference === item.unique_reference);

            if (quoteItem) {
                quoteItem.quantity = newQuantity;
            }
        },
        clearQuote() {
            this.quote = [];
        },
        calculateSubtotal(itemReference) {
            // Calculate the subtotal for a specific item
            const quoteItem = this.quote.find(item => item.unique_reference === itemReference);
            return quoteItem ? quoteItem.quantity * quoteItem.price : 0;
        },

    }
})