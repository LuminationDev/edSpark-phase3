import { defineStore } from "pinia";
import axios from 'axios';
import {serverURL} from "@/js/constants/serverUrl";

export const useSoftwareStore = defineStore('software', {
    state: () => ({
        articles: []
    }),

    getters: {
        getArticles() {
            return this.articles;
        }
    },

    actions: {
        async loadArticles() {
            return new Promise(async (resolve, reject) => {
                await axios.get(`${serverURL}/fetchSoftwarePosts`).then(response => {
                    const dashboardSoftware = [];
                    response.data.forEach(software => {
                        dashboardSoftware.push(software);
                    });

                    this.articles = dashboardSoftware;

                    resolve(dashboardSoftware);
                }).catch(error => {
                    console.log('Sorry, there was a problem retrieving the Advice Articles');
                    console.error(error);
                    reject(error.code);
                });
            });
        }
    }
})
