import { defineStore } from "pinia";
import axios from "axios";

export const useEventsStore = defineStore('events', {
    state: () => ({
        events: []
    }),

    getters: {
        getEvents() {
            return this.events;
        }
    },

    actions: {
        async loadEvents() {
            return new Promise(async (resolve, reject) => {
                await axios.get('http://localhost:8000/api/fetchEventPosts').then(res => {
                    this.events = res.data;
                    resolve(res.data);
                }).catch(err => {
                    console.log('There was an error!');
                    console.error(err);
                    reject(err.code);
                })
            })
        }
    }
})
