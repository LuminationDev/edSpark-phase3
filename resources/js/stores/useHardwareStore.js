import { defineStore } from "pinia";
import axios from 'axios';

export const useHardwareStore = defineStore('hardware', {
    state: () => ({
        allHardware: []
    }),

    getters: {
        getAllHardware() {
            return this.allHardware;
        }
    },

    actions: {
        async loadAllArticles() {
                await axios.get(`http://localhost:8000/api/fetchAllProducts`).then(response => {
                    return response.data
                }).catch(error => {
                    console.log('Error');
                    console.error(error.code);
            });
        },

        async loadProductsByBrand(brand) {
                return axios.get(`http://localhost:8000/api/fetchProductByBrand/${brand}`).then(response => {
                    return response.data
                }).catch(error => {
                    console.log('Error!!');
                    console.error(error);
                })
        }
    }
})
