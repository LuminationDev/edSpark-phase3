import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import { defineStore } from "pinia";
import axios from 'axios';
import {serverURL} from "@/js/constants/serverUrl";

export const useHardwareStore = defineStore('hardware', {
    state: () => ({
        allHardware: [],
        recommendedHardware: [],
        relatedBrandHardware: []
    }),

    getters: {
        getAllHardware() {
            return this.allHardware;
        }
    },

    actions: {

        async loadProductsByBrand(brand) {
                axios.get(`${API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_BY_BRAND}${brand}`).then(response => {
                    this.relatedBrandHardware = response.data
                }).catch(error => {
                    console.log('Error!!');
                    console.error(error);
                })
        },
        async loadRecommendedHardware(id){
            const payload = {
                currentId : id
            }
            axios.get(`${API_ENDPOINTS.HARDWARE.FETCH_RELATED_HARDWARE}`,payload).then(response => {
                this.recommendedHardware = response.data
            }).catch(error => {
                console.log('Error!!');
                console.error(error);
            })
        }
    }
})
