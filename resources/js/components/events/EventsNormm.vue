<script setup>


import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {onMounted, reactive, ref} from "vue";
import {useRoute} from "vue-router";

import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

import Spinner from "../spinner/Spinner.vue";

const state = reactive({currentUserEMSLink: ''})
const rules = {currentUserEMSLink: {required}}
const v$ = useVuelidate(rules, state)
const currentUserHasProvidedTheEMSLink = ref(false)
const editingEMSLink = ref(true)

const route = useRoute()
const isLoading = ref(false)

const handleClickEditLink = () => {
    console.log('Clicked Edit Button')
    editingEMSLink.value = true
}

const getEMSLink = () => {
    const urlWithLink = `${API_ENDPOINTS.EVENT.FETCH_EMS_LINK}${route.params.id}`
    axios.get(urlWithLink).then(res => {
        state.currentUserEMSLink = res.data.data.ems_link
        currentUserHasProvidedTheEMSLink.value = true
        console.log('mount function working')
    }).catch(error => {
        rsvpError.value = error.message
    })
}

onMounted(() => {
    getEMSLink()
})

const handleClickSubmitEvent = () => {
    console.log('button pressed')
    //console.log(v$.value.currentUserEMSLink.$model)
    //console.log(route.params.id)
    isLoading.value = true
    const data = {
        event_id: route.params.id,
        ems_link: v$.value.currentUserEMSLink.$model
    }
    axios.post(API_ENDPOINTS.EVENT.ADD_OR_EDIT_EMS_LINK, data).then(ree => {
        console.log(ree.data)
        editingEMSLink.value = true
    }).catch(error => {
        rsvpError.value = error.message
        console.log(error)
    }).finally(() => {
        isLoading.value = false
        console.log("its loading")
    })
}
</script>

<template>
    <div class="EventRsvpFormContainer bg-blue-900 mt-5 px-4 py-4 text-white">
        <div class="font-bold rsvpHeader text-2xl">
            Register for this event!
        </div>
        <div class="border-b-4 border-b-white flex flex-col py rsvpSubheader text-lg">
            <div class="eventTypeDescriptor pb-2 pt-4">
                This event is Simple type.
            </div>
            <div class="eventRsvp form-cta pb-4">
                Please register your interest below to reserve your place!
            </div>
        </div>
        <template v-if="editingEMSLink">
            <TextInput
                v-model="v$.currentUserEMSLink.$model"
                class="py-3"
                field-id="userSubmitLinkText"
                :v$="v$"
                placeholder="Enter the link here!"
                :with-no-left-margin="true"
            >
                <template #label>
                    Insert EMS link
                </template>
            </TextInput>
        </template>
        <!--        {{ rsvpError }}-->
        <GenericButton
            :callback="handleClickSubmitEvent"
            :disabled="isLoading"
        >
            <template #default>
                <Spinner v-if="isLoading" />
                {{ isLoading ? '' : 'Submit Please' }}
            </template>
        </GenericButton>
        <div class="border-b-4 border-b-white flex flex-col py-3 rsvpHeader text-lg" />

        <!--        Used to fetch the data!-->
        <template v-if="editingEMSLink">
            <label>
                {{ state.currentUserEMSLink }}
            </label>
        </template>
        <!--        {{ rsvpError }}-->
        <GenericButton
            :callback="getEMSLink"
            :disabled="isLoading"
        >
            <template #default>
                Fetch data
            </template>
        </GenericButton>
    </div>
</template>
