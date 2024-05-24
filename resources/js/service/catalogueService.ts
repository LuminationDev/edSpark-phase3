import axios from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {catalogueImageURL} from "@/js/constants/serverUrl";

type CatalogueFieldTypes = 'brand' | 'type' | 'vendor' | 'category'

export const catalogueService = {
    fetchCatalogueByField: (fieldType: CatalogueFieldTypes, fieldValue: Array<string>, additionalFilters, nthPage: number = 1, perPage: number = 20) => {
        const body = {field: fieldType, value: fieldValue, additional_filters: additionalFilters, per_page: perPage}
        const params = {page: nthPage}
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_CATALOGUE_BY_FIELD, body, {params: params})
    },

    fetchSingleProductByName: (name: string) => {
        const body = {name: name};
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_SINGLE_PRODUCT_BY_NAME, body);
    },
    fetchSingleProductByReference: (reference: string) => {
        const body = {unique_reference: reference};
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_SINGLE_PRODUCT_BY_REFERENCE, body);
    },

    fetchUpgradesSingleProduct: (name: string) => {
        const body = {name: name};
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_UPGRADES_SINGLE_PRODUCT, body);
    },

    fetchBundlesSingleProduct: (name: string) => {
        const body = {name: name};
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_BUNDLES_SINGLE_PRODUCT, body);
    },

    fetchAllCategories: () => {
        return axios.get(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE_CATEGORIES)
    },
    fetchAllTypes: () => {
        return axios.get(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE_TYPES)
    },
    fetchAllBrands: () => {
        return axios.get(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE_BRANDS)
    },
    fetchAllVendors: () => {
        return axios.get(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE_VENDORS)
    },
    fetchAllCatalogue: (per_page) => {
        const body = {per_page: per_page}
        return axios.post(API_ENDPOINTS.CATALOGUE.FETCH_ALL_CATALOGUE, body)
    },
    getCatalogueShortSpecObj: (catItem) => {
        const ComputerTypes = ['all-in-one', 'chromebook', 'desktop', 'notebook']
        const DisplayTypes = ['monitor', 'tablet']
        if (!catItem.type) return {}
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
    },

    getGroupedItemData: (item) => {
        return [
            {
                name: 'brand',
                value: item.brand,
                display_text: "Brand",
                group: 'overview'
            },
            {
                name: 'type',
                value: item.type,
                display_text: "Type",
                group: 'overview'
            },
            {
                name: 'category',
                value: item.category,
                display_text: "Category",
                group: 'hidden'
            },
            {
                name: 'vendor',
                value: item.vendor,
                display_text: "Vendor",
                group: 'overview'
            },
            {
                name: 'warranty',
                value: item.warranty,
                display_text: "Warranty",
                group: 'overview'
            },
            {
                name: 'price_inc_gst',
                value: item.price_inc_gst,
                display_text: "Price (incl. GST)",
                group: 'overview'
            },
            {
                name: 'price_expiry',
                value: item.price_expiry,
                display_text: "Price Expiry",
                group: 'overview'
            },
            {
                name: 'available_now',
                value: item.available_now,
                display_text: "Available Now",
                group: 'hidden'
            },
            {
                name: 'storage',
                value: item.storage,
                display_text: "Storage",
                group: 'specs'
            },
            {
                name: 'memory',
                value: item.memory,
                display_text: "Memory",
                group: 'specs'
            },
            {
                name: 'battery_life',
                value: item.battery_life,
                display_text: "Battery Life",
                group: 'specs'
            },
            {
                name: 'processor',
                value: item.processor,
                display_text: "Processor",
                group: 'specs'
            },
            {
                name: 'display',
                value: item.display,
                display_text: "Display",
                group: 'specs'
            },
            {
                name: 'graphics',
                value: item.graphics,
                display_text: "Graphics",
                group: 'specs'
            },
            {
                name: 'operating_system',
                value: item.operating_system,
                display_text: "Operating System",
                group: 'specs'
            },
            {
                name: 'weight',
                value: item.weight,
                display_text: "Weight",
                group: 'specs'
            },
            {
                name: 'wireless',
                value: item.wireless,
                display_text: "Wireless",
                group: 'specs'
            },
            {
                name: 'webcam',
                value: item.webcam,
                display_text: "Webcam",
                group: 'specs'
            },
            {
                name: 'form_factor',
                value: item.form_factor,
                display_text: "Form Factor",
                group: 'specs'
            },
            {
                name: 'stylus',
                value: item.stylus,
                display_text: "Stylus",
                group: 'specs'
            },
            {
                name: 'id',
                value: item.id,
                display_text: "ID",
                group: 'hidden'
            },
            {
                name: 'unique_reference',
                value: item.unique_reference,
                display_text: "Unique Reference",
                group: 'hidden'
            },
            {
                name: 'name',
                value: item.name,
                display_text: "Name",
                group: 'hidden'
            },
            {
                name: 'image',
                value: item.image,
                display_text: "Image",
                group: 'hidden'
            },
            {
                name: 'product_number',
                value: item.product_number,
                display_text: "Product Number",
                group: 'hidden'
            },
            {
                name: 'other',
                value: item.other,
                display_text: "Other",
                group: 'hidden'
            },
            {
                name: 'corporate',
                value: item.corporate,
                display_text: "Corporate",
                group: 'hidden'
            },
            {
                name: 'administration',
                value: item.administration,
                display_text: "Administration",
                group: 'hidden'
            },
            {
                name: 'curriculum',
                value: item.curriculum,
                display_text: "Curriculum",
                group: 'hidden'
            },
            {
                name: 'cover_image',
                value: item.cover_image,
                display_text: "Cover Image",
                group: 'hidden'
            }
            ]
    },

    getCatalogueCoverImage: (coverImage: { uuid: string, extension: string }) => {
        try {
            if (coverImage.extension) {
                return `${catalogueImageURL}/${coverImage.uuid}/original.${coverImage.extension.toLowerCase()}`
            } else
                return ''
        } catch (e) {
            return ''
        }
    },
    getExcGstPrice: (priceIncGst: number) => {
        if (priceIncGst) {
            return (priceIncGst / 1.1).toFixed(2)
        } else {
            return 0
        }
    }
}