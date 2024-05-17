import axios from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const quoteService = {
    getCart: async () =>{
        return axios.get(`${API_ENDPOINTS.CART.GET_CART}`)
    },
    addItemToCart: async () =>{
        const data = {}
        return axios.post(`${API_ENDPOINTS.CART.ADD_ITEM_TO_CART}`)
    },
    updateItemQuantityInCart: async (itemRef) =>{
        return axios.put(`${API_ENDPOINTS.CART.UPDATE_QUANTITY_CART}/${itemRef}/update`)
    },
    deleteItemInCart: async (itemRef) =>{
        return axios.delete(`${API_ENDPOINTS.CART.DELETE_ITEM_CART}/${itemRef}`)
    },
    clearCart: async () =>{
        return axios.delete(`${API_ENDPOINTS.CART.CLEAR_CART}`)
    },
    checkoutCart: async () =>{
        return axios.post(`${API_ENDPOINTS.CHECKOUT_CART}`)
    }
}