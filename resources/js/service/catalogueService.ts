import axios from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

type CatalogueFieldTypes = 'brand'|'type'|'vendor'|'category'

export const catalogueService = {
    fetchCatalogueByField: (fieldType: CatalogueFieldTypes, fieldValue: Array<string> ,nthPage: number = 1, perPage: number = 20) =>{
        const body = {field : fieldType, value: fieldValue, per_page: perPage}
        const params = {page: nthPage }
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_CATALOGUE_BY_FIELD, body, {params: params})
    },
    fetchBrandCatalogue: (value: string, nthPage: number, perPage: number = 20) => {
        return catalogueService.fetchCatalogueByField('brand', value, nthPage, perPage);
    },

    fetchTypeCatalogue: (value: string, nthPage: number, perPage: number = 20) => {
        return catalogueService.fetchCatalogueByField('type', value, nthPage, perPage);
    },

    fetchVendorCatalogue: (value: string, nthPage: number, perPage: number = 20) => {
        return catalogueService.fetchCatalogueByField('vendor', value, nthPage, perPage);
    },

    fetchCategoryCatalogue: (value: string, nthPage: number, perPage: number = 20) => {
        return catalogueService.fetchCatalogueByField('category', value, nthPage, perPage);
    },
    fetchSingleProductByName: (name: string) => {
        const body = { name: name };
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_SINGLE_PRODUCT_BY_NAME, body);
    },
    fetchSingleProductByReference: (reference: string) => {
        const body = { unique_reference: reference };
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_SINGLE_PRODUCT_BY_REFERENCE, body);
    },

    fetchUpgradesSingleProduct: (name: string) => {
        const body = { name: name };
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_UPGRADES_SINGLE_PRODUCT, body);
    },

    fetchBundlesSingleProduct: (name: string) => {
        const body = { name: name };
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_BUNDLES_SINGLE_PRODUCT, body);
    },

    fetchAllCategories: () =>{
        return axios.get(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE_CATEGORIES)
    },
    fetchAllTypes: () =>{
        return axios.get(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE_TYPES)
    },
    fetchAllBrands: () =>{
        return axios.get(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE_BRANDS)
    },
    fetchAllVendors: () =>{
        return axios.get(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE_VENDORS)
    },
    fetchAllCatalogue: (per_page)=>{
        const body = {per_page: per_page}
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE,body)
    },
    getCatalogueShortSpecObj: (catItem) =>{
        const ComputerTypes = ['all-in-one', 'chromebook', 'desktop', 'notebook']
        const DisplayTypes = ['monitor', 'tablet']
        if(!catItem.type) return {}
        if (ComputerTypes.includes(catItem.type.toLowerCase())) {
            return {
                'processor': catItem.processor,
                'memory': catItem.memory,
                'storage': catItem.storage
            }
        } else if (DisplayTypes.includes(catItem.type.toLowerCase())) {
            return {
                'display': catItem.display,
                'other': catItem.other
            }
        } else {
            return {}
        }
    }
}