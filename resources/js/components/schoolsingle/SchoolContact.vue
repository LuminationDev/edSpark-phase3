<script setup>

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import { ref, reactive, onMounted } from "vue";
import { required, minLength, email, numeric } from '@vuelidate/validators'
import axios from "axios";
import { GoogleMap, Marker } from 'vue3-google-map'
import useVuelidate from '@vuelidate/core'


import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import { serverURL } from "@/js/constants/serverUrl";


const props = defineProps({
    schoolId: {
        type: [Number, String],
        required: true
    },
    schoolLocation: {
        type: Object,
        required: false,
        default: () => ({ lat: 0, lng: 0 })
    },
    currentUserCanEdit: {
        type: Boolean,
        required: true
    }
})


const editMode = ref(false)
const contactInfoAvailable = ref(false)
const state = reactive({
    location: "",
    website: "",
    email: '',
    phone: "",
    fax: ''

})

const rules = {
    location: { required, minLength: minLength(10) },
    website: { required },
    email: { required, email },
    phone: { required, minLength: minLength(9) },
    fax: {}
}

const v$ = useVuelidate(rules, state)

const handleSaveContactForm = () => {
    v$.value.$validate();
    if (!v$.$errors) {
        console.log(JSON.stringify(state))
        let sendContactDataBody = {
            school_id: props.schoolId,
            school_contact: JSON.stringify(state)
        }
        axios.post(API_ENDPOINTS.SCHOOL.CREATE_OR_UPDATE_SCHOOL_CONTACT, sendContactDataBody).then(res => {
            editMode.value = false
        })

    } else {
        console.log('not sending mate, there is error')
    }
}

const handleToggleEditContactForm = () => {
    editMode.value = !editMode.value
}

let contactRequestBody = {
    school_id: props.schoolId
}
axios.post(API_ENDPOINTS.SCHOOL.FETCH_SCHOOL_CONTACT, contactRequestBody).then(res => {
    console.log(res.data)
    if (res.data.result) {
        const parsedData = typeof res.data.school_contact == 'string' ? JSON.parse(res.data.school_contact) : res.data.school_contact
        state.location = parsedData.location || ""
        state.website = parsedData.website || ""
        state.email = parsedData.email || ""
        state.phone = parsedData.phone || ""
        state.fax = parsedData.fax || ""
        contactInfoAvailable.value = true

        console.log(parsedData)
    } else {
        console.log('schoolDataNotFound')
    }
}).catch(err => {
    console.log()
})


const mapOptions = {
    center: {
        lat: props.schoolLocation.lat,
        lng: props.schoolLocation.lng
    },
    zoom: 18,
    options: {
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullScreenControl: false,
        mapId: '248cf49f3df3c73d'
    }
};



</script>

<template>
    <div class="SchoolContactSubPageContainer flex flex-col gap-10 px-5 py-2 text-genericDark md:!px-10 lg:!flex-row">
        <div class="contactEditAndDisplayContainer flex flex-col w-full">
            <div class="contactSubPageTitle font-semibold text-xl">
                Contact details
            </div>
            <div
                v-if="editMode && currentUserCanEdit"
                class="contactForm flex flex-col"
            >
                <div class="contactFormTitle font-semibold mb-4 text-xl">
                    School Contact Form
                </div>
                <TextInput
                    v-model="v$.location.$model"
                    field-id="schoolLocation"
                    :v$="v$.location"
                    placeholder="Enter School Address"
                >
                    <template #label>
                        Location
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.website.$model"
                    field-id="schoolLocation"
                    :v$="v$.website"
                    placeholder="Enter School Website"
                >
                    <template #label>
                        Website
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.email.$model"
                    field-id="schoolLocation"
                    :v$="v$.email"
                    placeholder="Enter School Email Address"
                >
                    <template #label>
                        Email Address
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.phone.$model"
                    field-id="schoolLocation"
                    :v$="v$.phone"
                    placeholder="Enter School Phone Number"
                >
                    <template #label>
                        Phone Number
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.fax.$model"
                    field-id="schoolLocation"
                    :v$="v$.fax"
                    placeholder="Enter Fax Number"
                >
                    <template #label>
                        Fax Number
                    </template>
                </TextInput>
                <GenericButton
                    :callback="handleSaveContactForm"
                    custom-class="mt-4"
                >
                    <template #default>
                        Submit Contact Information
                    </template>
                </GenericButton>
            </div>
            <div
                v-else-if="contactInfoAvailable"
                class="displayContact flex flex-col p-4 pt-8"
            >
                <template
                    v-for="(value, key) in state"
                    :key="key"
                >
                    <div class="flex flex-row my-4">
                        <div class="flex justify-end font-semibold mr-4 text-base w-1/4 md:!text-xl">
                            {{ key.toUpperCase() }}
                        </div>
                        <div class="flex flex-wrap text-sm w-3/4 md:!text-lg">
                            <p class="break-words text-ellipsis w-full">
                                {{ state[key] }}
                            </p>
                        </div>
                    </div>
                </template>
            </div>
            <div
                v-else
                class="available contact flex flex-col no p-4 pt-8"
            >
                Sorry contact not available yet. Please check back later
            </div>
            <GenericButton
                v-if="currentUserCanEdit"
                :callback="handleToggleEditContactForm"
                class="mt-2"
            >
                <template #default>
                    {{ editMode ? "Cancel Edit" : "Edit Contact" }}
                </template>
            </GenericButton>
        </div>

        <div class="schoolContactMapContainer w-full">
            <GoogleMap
                api-key="AIzaSyAFbqxGQntzgzfzKFh6bArwU14MJhcV1Wc"
                style="width: 100%; height: 700px"
                :options="mapOptions.options"
                :center="mapOptions.center"
                :zoom="mapOptions.zoom"
            >
                <Marker
                    class="relative"
                    :options="{ position: { lat: props.schoolLocation.lat, lng: props.schoolLocation.lng } }"
                />
            </GoogleMap>
        </div>
    </div>
</template>
