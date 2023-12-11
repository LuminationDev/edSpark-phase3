<script setup lang="ts">
import useVuelidate from "@vuelidate/core/dist/index";
import {numeric, required} from "@vuelidate/validators";
import axios from "axios";
import {storeToRefs} from "pinia";
import {reactive, ref} from 'vue'

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";


const allSites = ref([])
const userStore = useUserStore()

const state = reactive({
    emsLink: userStore.getEMSlink,
    schoolName: userStore.getUserSiteName,
    numOfGuest: "",
    currentUserEMSLink: ''
})

const rules = {
    fullName: {required},
    schoolName: {required},
    numOfGuest: {required, numeric},
    currentUserEMSLink: {required, numeric}
}
const v$ = useVuelidate(rules, state)

axios.get(API_ENDPOINTS.SCHOOL.FETCH_ALL_SITES).then(res => {
    allSites.value = res.data
}).catch(err => {
    console.error(err);
    console.log('Theres an error');
})


</script>


<!--    Form no 1 - conditional, user=owner && EMS=yes-->
<template
    v-if="(currentUserIsOwner && currentUserHasProvidedEMSLink && eventStatus !== 'ENDED') || editingEMSlink"
>
    <form
        class="border-b-2 border-white flex flex-col gap-2 py-8 rsvpFormInputs"
        autocomplete="off"
        @submit.prevent=""
    >
        <div class="-mt-1 Selector dropdown flex flex-col school">
            <label class="-mb-2 ml-2"> You have provided the
                Link<br><br><i>https://www.edspark.com.au/....</i></label>

            <ErrorMessages
                :v$="v$.schoolName"
            />
        </div>
        <div class="flex items-center flex-row">
            <GenericButton
                id="rsvpBtn"
                :callback="handleSubmitRsvp"
                class="!bg-secondary-coolGrey !text-black font-semibold mt-4 px-6 rounded-sm w-fit"
            >
                <template #default>
                    Edit
                </template>
            </GenericButton>
            <span
                v-if="rsvpError"
                class="cursor-pointer font-semibold mt-4 px-4 text-red-500"
                @click="rsvpError = ''"
            > {{ rsvpError }}</span>
        </div>
    </form>
</template>
<style></style>
