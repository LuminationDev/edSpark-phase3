import {useStorage} from "@vueuse/core";
import {defineStore} from "pinia";

export const useCatalogueStore = defineStore('catalogue', {
    state: () => ({
        catalogueList: useStorage('EDSPARK_CATALOGUE_ITEMS', [], localStorage, {mergeDefaults: true}),
        catalogueExpiry: useStorage('EDSPARK_CATALOGUE_EXPIRY', 0, localStorage, {mergeDefaults: true}),
        compareBasket: useStorage('EDSPARK_COMPARE_BASKET', [], sessionStorage),
        categoryList: [],
        brandList: [],
        typeList: [],
        vendorList: [],
        processorList: [],
        memoryList: [],
        storageList: [],
        primaryFilter: '',
        selectedCategory: [],
        selectedBrand: [],
        selectedType: [],
        selectedVendor: [],
        selectedProcessor: [],
        selectedMemory: [],
        selectedStorage: [],
        priceRange: [0, 30000],
        searchKeyword: '',


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
            }

        },
        removeItemFromComparisonBasket(itemUniqueRef) {
            this.compareBasket = this.compareBasket.filter(item => item.unique_reference !== itemUniqueRef)
        },
        clearComparisonBasket() {
            this.compareBasket = []
        },
        // end of comparisons
        resetFilters() {
            this.primaryFilter = ''
            this.selectedCategory = []
            this.selectedBrand = []
            this.selectedType = []
            this.selectedVendor = []
            this.selectedProcessor = []
            this.selectedMemory = []
            this.selectedStorage = []
            this.priceRange = [0, 30000]
            this.searchKeyword = ''
        },

    },
});
