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
        loadEvents() {
            axios.get('http://localhost:8000/api/fetchEventPosts').then(res => {
                console.log(res.data);
                this.events = res.data;
            }).catch(err => {
                console.log('There was an error!');
                console.error(err);
            })
        }
    }
})
