import {useStorage} from "@vueuse/core";
import {defineStore} from "pinia";

import {quoteService} from "@/js/service/quoteService";
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
        async initializeQuote(){
            try{
                this.quoteLoading = true;
                const quoteFromDb = await quoteService.getCart()
                console.log(quoteFromDb)
                this.quote = quoteFromDb;
                this.quoteLoading = false;
            } catch (e){
                console.log(e)
            }
        },
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
        calculateSubtotalPerVendor(vendor: string) {
            const groupedQuote = this.getQuoteGroupedByVendor
            if(groupedQuote[vendor]) {
                const items = groupedQuote[vendor]
                let subtotal = 0
                for(const item of items){
                    subtotal += (+item.price_inc_gst * +item.quantity)
                }
                return subtotal.toFixed(2)
            } else{
                console.log('vendor not exist')
                return 0
            }
        },
        isItemInQuote(itemReference: string) {
            if (!this.quote || this.quote.length <= 0) {
                return false
            }
            if (this.quote.some(item => item.unique_reference === itemReference)) {
                return true
            }
        }

    }
})