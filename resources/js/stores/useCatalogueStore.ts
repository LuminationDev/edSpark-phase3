import {useStorage} from "@vueuse/core";
import {defineStore} from "pinia";

export const CatalogueStore = defineStore('catalogue', {
    state: () => ({
        cart: useStorage('EDSPARK_CATALOGUE_CART', [], localStorage, {mergeDefaults: true}),
    }),
    getters: {
        getCatalogue() {
            // Return your catalogue data
        },
        getCart() {
            return this.cart;
        },
        getCartCount() {
            return this.cart.length;
        },
        getCartTotalPrice() {
            return this.cart.reduce((total, item) => total + item.quantity * item.price, 0);
        },
        getItemQuantity(itemId) {
            // Get the quantity of a specific item in the cart
            const cartItem = this.cart.find(item => item.id === itemId);
            return cartItem ? cartItem.quantity : 0;
        },
        isItemInCart(itemId) {
            // Check if a specific item is in the cart
            return this.cart.some(item => item.id === itemId);
        },
    },
    actions: {

        addToCart(item) {
            const existingItem = this.cart.find(cartItem => cartItem.id === item.id);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                this.cart.push({...item, quantity: 1});
            }
        },
        removeFromCart(itemId) {
            this.cart = this.cart.filter(item => item.id !== itemId);
        },
        changeQuantity(item, newQuantity) {
            const cartItem = this.cart.find(cartItem => cartItem.id === item.id);

            if (cartItem) {
                cartItem.quantity = newQuantity;
            }
        },
        clearCart() {
            this.cart = [];
        },
        calculateSubtotal(itemId) {
            // Calculate the subtotal for a specific item
            const cartItem = this.cart.find(item => item.id === itemId);
            return cartItem ? cartItem.quantity * cartItem.price : 0;
        },
    },
});
