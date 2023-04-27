import { defineStore } from "pinia";
import axios from 'axios';

export const useAdviceStore = defineStore('advice', {
    state: () => ({
        resources: [],
    }),

    getters: {
        getResources() {
            return this.resources;
        }
    },

    actions: {
        async loadDashboardResources() {
            return new Promise(async (resolve, reject) => {
                await axios.get('http://localhost:8000/api/fetchAdvicePosts').then(response => {
                    const dashboardAdvice = [];
                    response.data.forEach( async advice => {
                        dashboardAdvice.push(advice);
                    });
                    this.resources = dashboardAdvice;

                    resolve(dashboardAdvice);
                }).catch(error => {
                    console.log('Sorry, there was a problem retrieving the Advice Articles');
                    console.error(error);
                    reject(error.code);
                });
            });
        }
    }
})
