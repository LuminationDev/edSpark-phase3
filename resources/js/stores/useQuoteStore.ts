import {useStorage} from "@vueuse/core";
import {removed} from "dompurify";
import {cloneDeep} from "lodash";
import {defineStore} from "pinia";
import {toast} from "vue3-toastify";

import {quoteService} from "@/js/service/quoteService";
import {CatalogueItemType} from "@/js/types/catalogueTypes";

export const useQuoteStore = defineStore('quote', {
    state: () => ({
        quote: useStorage('EDSPARK_CATALOGUE_QUOTE', [], localStorage, {mergeDefaults: true}),
        qouteCreationDate: useStorage('EDSPARK_QUOTE_CREATION_DATE', 0, localStorage),
        qouteUpdateDate: useStorage('EDSPARK_QUOTE_UPDATE_DATE', 0, localStorage),
        quoteLoading: false,
        quoteVendorInfo: {},
        quotePreview: {},
        genQuote: []

    }),
    getters: {
        getQuote() {
            return this.quote
        },
        getQuoteGroupedByVendor: (state) => {
            console.log(state.quote)
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
        async initializeQuote() {
            try {
                this.quoteLoading = true;
                const quoteFromDb = await quoteService.getCart()
                this.quote = quoteFromDb;
                this.quoteLoading = false;
            } catch (e) {
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
        async removeFromQuote(itemReference) {
            const oldQuote = cloneDeep(this.quote)
            this.quote = this.quote.filter(item => item.unique_reference !== itemReference);
            try {
                await quoteService.deleteItemInCart(itemReference)
            } catch (err) {
                this.quote = oldQuote
                console.error('Failed to delete item, reverting to previous value', err.message)
                throw new Error('Failed to delete item, reverting to previous value')
            }
        },


        async changeQuantity(item, newQuantity) {
            const quoteItem = this.quote.find(quoteItem => quoteItem.unique_reference === item.unique_reference);

            if (quoteItem) {
                const oldQuantity = quoteItem.quantity;
                if (+newQuantity === 0) {
                    const oldQuote = cloneDeep(this.quote)
                    this.removeFromQuote(quoteItem.unique_reference)
                    try {
                        await quoteService.deleteItemInCart(quoteItem.unique_reference)
                    } catch (err) {
                        this.quote = cloneDeep(oldQuote)
                    }
                } else {
                    quoteItem.quantity = newQuantity;
                    try {
                        await quoteService.updateItemQuantityInCart(item.unique_reference, newQuantity)
                    } catch (err) {
                        console.log(oldQuantity)
                        quoteItem.quantity = oldQuantity
                    }
                }
            }

        },

        async clearQuoteByVendor(vendorName) {
            const oldQuote = cloneDeep(this.quote)
            const removedItems = []
            console.log('removing vendor name from cart')
            try {
                this.quote.forEach(item => {
                    if (item.vendor === vendorName) {
                        this.removeFromQuote(item.unique_reference)
                        removedItems.push(item)
                    }
                })

            } catch (err) {
                console.log('Error removing items for vendors')
                this.quote = cloneDeep(oldQuote)
                removedItems.forEach((item) =>{
                    this.addToQuote(item)
                })
            }


        },
        async clearQuote() {
            const oldQuote = cloneDeep(this.quote)
            this.quote = [];
            try {
                await quoteService.clearCart()
            } catch (err) {
                console.log('failed to empty cart ' + err.message)
                this.quote = cloneDeep(oldQuote)
                toast.error("Failed to empty cart. Try again")
            }

        },
        calculateSubtotal(itemReference) {
            // Calculate the subtotal for a specific item
            const quoteItem = this.quote.find(item => item.unique_reference === itemReference);
            return quoteItem ? quoteItem.quantity * quoteItem.price : 0;
        },
        calculateSubtotalPerVendor(vendor: string) {
            const groupedQuote = this.getQuoteGroupedByVendor
            if (groupedQuote[vendor]) {
                const items = groupedQuote[vendor]
                let subtotal = 0
                for (const item of items) {
                    subtotal += (+item.price_inc_gst * +item.quantity)
                }
                return subtotal.toFixed(2)
            } else {
                console.log('vendor not exist')
                return 0
            }
        },
        isItemInQuote(itemReference: string) {
            if (this.quote.length <= 0) {
                return false
            }
            if (this.quote.some(item => item.unique_reference === itemReference)) {
                return true
            }
        },
        async checkoutVendor(vendor: string) {
            try {
                await quoteService.checkoutCart(vendor)
            } catch (err) {
                console.log(err.message)
            } finally {
                await this.initializeQuote()
            }
        },
        async getUserGenQuote() {
            try {
                this.genQuote = await quoteService.getGenQuote()
            } catch (err) {
                console.error("Failed to fetch generated quotes " + err.message)
            }
        }

    }
})