import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import { defineStore } from "pinia";
import axios from 'axios';
import {serverURL} from "@/js/constants/serverUrl";

export const useHardwareStore = defineStore('hardware', {
    state: () => ({
        allHardware: [],
        relatedHardware: [],
        relatedBrandHardware: []
    }),

    getters: {
        getAllHardware() {
            return this.allHardware;
        }
    },

    actions: {

        async loadProductsByBrand(id) {
            const payload ={
                currentId: id,
            }
                axios.post(API_ENDPOINTS.HARDWARE.FETCH_HARDWARE_BY_BRAND, payload).then(response => {
                    this.relatedBrandHardware = response.data
                }).catch(error => {
                    console.log('Error!!');
                    console.error(error);
                })
        },
        async loadRelatedHardware(id){
            const payload = {
                currentId : id
            }
            axios.post(`${API_ENDPOINTS.HARDWARE.FETCH_RELATED_HARDWARE}`,payload).then(response => {
                this.relatedHardware = response.data
            }).catch(error => {
                console.log('Error!!');
                console.error(error);
            })
        }
    }
})
