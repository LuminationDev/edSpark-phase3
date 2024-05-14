import {useStorage} from "@vueuse/core";
import {defineStore} from "pinia";

export const useCatalogueStore = defineStore('catalogue', {
    state: () => ({
        catalogueList: useStorage('EDSPARK_CATALOGUE_ITEMS', [], localStorage, {mergeDefaults: true}),
        catalogueExpiry:useStorage('EDSPARK_CATALOGUE_EXPIRY', 0, localStorage, {mergeDefaults: true}),
        compareBasket: useStorage('EDSPARK_COMPARE_BASKET', []),
        categoryList: useStorage('EDSPARK_CATALGUE_CAT', []),
        brandList: useStorage('EDSPARK_CATALGUE_BRAND', []),
        typeList: useStorage('EDSPARK_CATALGUE_TYPE', []),
        vendorList: useStorage('EDSPARK_CATALGUE_VENDOR', [])
    }),
    getters: {
        getCompareBasketItem() {
            return this.compareBasket;
        },
        getCompareBasketLength() {
            if (!this.compareBasket) return 0
            else return this.compareBasket.length
        },
        showCompareBanner() {
            return Boolean(this.compareBasket.length)
        }
    },
    actions: {
        // comparisons
        addItemToComparisonBasket(item) {
            if (!this.compareBasket.some(currentItem => currentItem.unique_reference == item.unique_reference)) {
                this.compareBasket.push(item)
            } else {
                console.log('eh heh almost added the same itemm twice')
            }
        },
        removeItemFromComparisonBasket(itemUniqueRef) {
            this.compareBasket = this.compareBasket.filter(item => item.unique_reference !== itemUniqueRef)
        },
        clearComparisonBasket() {
            this.compareBasket = []
        },
        // end of comparisons

    },
});
