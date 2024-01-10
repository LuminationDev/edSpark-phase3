import axios from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

type CatalogueFieldTypes = 'brand'|'type'|'vendor'|'category'

export const catalogueService = {
    fetchCatalogueByField: (fieldType: CatalogueFieldTypes, fieldValue: string ,nthPage: number = 1, perPage: number = 20) =>{
        console.log('fetch catalogue called ' + fieldType + " " + nthPage)
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

    fetchUpgradesSingleProduct: (name: string) => {
        const body = { name: name };
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_UPGRADES_SINGLE_PRODUCT, body);
    },

    fetchBundlesSingleProduct: (name: string) => {
        const body = { name: name };
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_BUNDLES_SINGLE_PRODUCT, body);
    },
}