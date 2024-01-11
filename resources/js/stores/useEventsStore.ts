import { defineStore } from "pinia";
import axios from "axios";
import {serverURL} from "@/js/constants/serverUrl";

export const useEventsStore = defineStore('events', {
    state: () => ({
        allEvents: []
    }),

    getters: {
        getEvents() {
            return this.events;
        }
    },

    actions: {
        async loadEvents() {
            return new Promise(async (resolve, reject) => {
                await axios.get(`${serverURL}/fetchEventPosts`).then(res => {
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
