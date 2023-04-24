import { defineStore } from "pinia";
import axios from 'axios';

export const useRolesStore = defineStore('roles', {
    state: () => ({
        roles: []
    }),

    getters: {
        getRoles() {
            return this.roles;
        },
    },

    actions: {
        loadRoles() {
            console.log('Can load the roles');
            /**
             * Axios to the API server to get all the roles listed
             * Maybe filter them, to only return allowed roles
             * But we'll see what Darren says
             */
        }
    }
})
