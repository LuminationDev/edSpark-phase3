import { defineStore } from "pinia";
import axios from 'axios';
import {serverURL} from "@/js/constants/serverUrl";

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
                await axios.get(`${serverURL}/fetchAllProducts`).then(response => {
                    return response.data
                }).catch(error => {
                    console.log('Error');
                    console.error(error.code);
            });
        },

        async loadProductsByBrand(brand) {
                return axios.get(`${serverURL}/fetchProductByBrand/${brand}`).then(response => {
                    return response.data
                }).catch(error => {
                    console.log('Error!!');
                    console.error(error);
                })
        }
    }
})
