import { defineStore } from "pinia";
import axios from 'axios';

export const useHardwareStore = defineStore('hardware', {
    state: () => {
        allHardware: []
    },

    getters: {
        getAllHardware() {
            return this.allHardware;
        }
    },

    actions: {
        async loadAllArticles() {
            return new Promise(async (resolve, reject) => {
                await axios.get(`http://localhost:8000/api/fetchAllProducts`).then(response => {
                    console.log(response.data);

                    resolve(response.data);
                }).catch(error => {
                    console.log('Error');
                    console.error(error.code);
                    reject(error.code);
                });
            });
        },

        async loadProductsByBrand(brand) {
            return new Promise(async (resolve, reject) => {
                await axios.get(`http://localhost:8000/api/fetchProductByBrand/${brand}`).then(response => {

                    resolve(response.data);
                }).catch(error => {
                    console.log('Error!!');
                    console.error(error);
                    reject(error.code);
                })
            })
        }
    }
})
