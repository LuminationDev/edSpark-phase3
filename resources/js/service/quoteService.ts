import axios from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const quoteService = {
    getCart: async () => {
        return axios.get(`${API_ENDPOINTS.CART.GET_CART}`).then(res => {
            return res.data
        })
    },
    addItemToCart: async (itemRef: string, quantity: number) => {
        const data = {
            unique_reference: itemRef,
            quantity: quantity
        }
        return axios.post(`${API_ENDPOINTS.CART.ADD_ITEM_TO_CART}`).then(res => {
            return res.data
        })
    },
    updateItemQuantityInCart: async (itemRef) => {
        return axios.put(`${API_ENDPOINTS.CART.UPDATE_QUANTITY_CART}/${itemRef}/update`).then(res => {
            return res.data
        })
    },
    deleteItemInCart: async (itemRef) => {
        return axios.delete(`${API_ENDPOINTS.CART.DELETE_ITEM_CART}/${itemRef}`).then(res => {
            return res.data
        })
    },
    clearCart: async () => {
        return axios.delete(`${API_ENDPOINTS.CART.CLEAR_CART}`).then(res => {
            return res.data
        })
    },
    checkoutCart: async () => {
        return axios.post(`${API_ENDPOINTS.CHECKOUT_CART}`).then(res => {
            return res.data
        })
    }
}