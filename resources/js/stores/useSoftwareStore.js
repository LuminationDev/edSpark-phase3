import { defineStore } from "pinia";
import axios from 'axios';

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
            await axios.get('http://localhost:8000/api/fetchSoftwarePosts').then(response => {
                console.log(response);
                const dashboardSoftware = [];
                response.data.forEach(software => {
                    if (software.software_type === 'Dashboard Featured') {
                        dashboardSoftware.push(software);
                    };
                });

                this.articles = dashboardSoftware;
                // this.articles = response.data;
            }).catch(error => {
                console.log('Sorry, there was a problem retrieving the Advice Articles');
                console.error(error);
            })
        }
    }
})
