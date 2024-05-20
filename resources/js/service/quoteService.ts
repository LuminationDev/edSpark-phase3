import axios from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

export const quoteService = {
    getCart: async () => {
        return axios.get(`${API_ENDPOINTS.CART.GET_CART}`).then(res => {
            return res.data
        }).catch(err =>{
            throw new Error( 'Failed to get cart' + err.message)
        })
    },
    addItemToCart: async (itemRef: string, quantity: number) => {
        const data = {
            unique_reference: itemRef,
            quantity: quantity
        }
        return axios.post(`${API_ENDPOINTS.CART.ADD_ITEM_TO_CART}`,data).then(res => {
            return res.data
        }).catch(err =>{
            throw new Error( 'Failed to add item to cart' + err.message)
        })
    },
    updateItemQuantityInCart: async (itemRef, quantity) => {
        const updatePutPayload = {
            quantity: quantity
        }
        return axios.put(`${API_ENDPOINTS.CART.UPDATE_QUANTITY_CART}/${itemRef}/update`, updatePutPayload).then(res => {
            return res.data
        }).catch(err =>{
            throw new Error( 'Failed to update item quantity' + err.message)
        })
    },
    deleteItemInCart: async (itemRef) => {
        return axios.delete(`${API_ENDPOINTS.CART.DELETE_ITEM_CART}/${itemRef}`).then(res => {
            return res.data
        }).catch(err =>{
            throw new Error( 'Failed to delete item from cart' + err.message)
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