<script setup lang="ts">

import useVuelidate from '@vuelidate/core'
import {email, minLength,  required} from '@vuelidate/validators'
import axios from "axios";
import { reactive, ref} from "vue";
import {GoogleMap, Marker} from 'vue3-google-map'

import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import useIsLoading from "@/js/composables/useIsLoading";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {schoolContactService} from "@/js/service/schoolContactService";


const props = defineProps({
    schoolId: {
        type: [Number, String],
        required: true
    },
    schoolLocation: {
        type: Object,
        required: false,
        default: () => ({lat: 0, lng: 0})
    },
    currentUserCanEdit: {
        type: Boolean,
        required: true
    }
})


const editMode = ref(false)
const contactInfoAvailable = ref(false)


const initiateFetchSchoolContact = (): Promise<any> => {
    console.log('init fetch school contact')
    return schoolContactService.fetchSchoolContact(+props.schoolId).then(res => {
        state['location'] = res.location
        state['website'] = res.website
        state['email'] = res.email
        state['phone'] = res.phone
        state['fax'] = res.fax
        contactInfoAvailable.value = true
    })
}
const {isLoading} = useIsLoading(initiateFetchSchoolContact)

const state = reactive({
    location: "",
    website: "",
    email: '',
    phone: "",
    fax: ''

})

const rules = {
    location: {required, minLength: minLength(10)},
    website: {required},
    email: {required, email},
    phone: {required, minLength: minLength(9)},
    fax: {}
}

const v$ = useVuelidate(rules, state)

const handleSaveContactForm = () => {
    v$.value.$validate();
    if (!v$.$errors) {
        const sendContactDataBody = {
            school_id: props.schoolId,
            school_contact: JSON.stringify(state)
        }
        axios.post(API_ENDPOINTS.SCHOOL.CREATE_OR_UPDATE_SCHOOL_CONTACT, sendContactDataBody).then(res => {
            editMode.value = false
            contactInfoAvailable.value = true

        })

    } else {
        console.log('not sending mate, there is error')
    }
}

const handleToggleEditContactForm = () => {
    editMode.value = !editMode.value
}


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
            <div class="contactSubPageTitle font-bold text-2xl">
                Contact details
            </div>
            <div v-if="isLoading">
                <Loader
                    :loader-color="'#0072DA'"
                    class="pt-4 font-thin"
                    :loader-message="'Contact detail loading'"
                />
            </div>
            <div
                v-else-if="editMode && currentUserCanEdit"
                class="contactForm flex flex-col"
            >
                <div class="contactFormTitle font-semibold mb-4 text-xl">
                    School contact form
                </div>
                <TextInput
                    v-model="v$.location.$model"
                    field-id="schoolLocation"
                    :v$="v$.location"
                    placeholder="Enter school address"
                >
                    <template #label>
                        Location
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.website.$model"
                    field-id="schoolLocation"
                    :v$="v$.website"
                    placeholder="Enter school website"
                >
                    <template #label>
                        Website
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.email.$model"
                    field-id="schoolLocation"
                    :v$="v$.email"
                    placeholder="Enter school email address"
                >
                    <template #label>
                        Email address
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.phone.$model"
                    field-id="schoolLocation"
                    :v$="v$.phone"
                    placeholder="Enter school phone number"
                >
                    <template #label>
                        Phone number
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.fax.$model"
                    field-id="schoolLocation"
                    :v$="v$.fax"
                    placeholder="Enter fax number"
                >
                    <template #label>
                        Fax number
                    </template>
                </TextInput>
                <GenericButton
                    :callback="handleSaveContactForm"
                    custom-class="mt-4"
                >
                    <template #default>
                        Submit contact information
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
                    <div
                        v-if="state[key]"
                        class="flex flex-row my-4"
                    >
                        <div class="capitalize flex justify-end font-semibold mr-4 text-base w-1/4 md:!text-xl">
                            {{ key }}
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
                Sorry, no contact information is available yet. Please check back later
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
