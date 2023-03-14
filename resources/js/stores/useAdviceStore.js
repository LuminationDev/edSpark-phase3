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
        async loadResources() {
            await axios.get('http://localhost:8000/api/fetchAdvicePosts').then(response => {
                console.log(response);
                const dashboardAdvice = [];
                response.data.forEach( async advice => {
                    if (advice.advice_type === 'Dashboard Featured') {
                        dashboardAdvice.push(advice);
                        console.log(advice);
                    }
                });
                this.resources = dashboardAdvice;

                // this.resources = response.data;
            }).catch(error => {
                console.log('Sorry, there was a problem retrieving the Advice Articles');
                console.error(error);
            })
        }
    }
})
