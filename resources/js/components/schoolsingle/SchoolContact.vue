<script setup>

import {ref, reactive, onBeforeMount} from "vue";
import {required, minLength, email, numeric} from '@vuelidate/validators'
import axios from "axios";
import {GoogleMap, Marker} from 'vue3-google-map'
import useVuelidate from '@vuelidate/core'


import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {serverURL} from "@/js/constants/serverUrl";


const props = defineProps({
    schoolId: {
        type: [Number, String],
        required: true
    },
    schoolLocation:{
        type:Object,
        required: false,
        default: () =>({lat: 0, lng:0})
    },
    currentUserCanEdit:{
        type: Boolean,
        required: true
    }
})


const editMode = ref(false)

const state = reactive({
    location:"",
    website: "",
    email:'',
    phone: "",
    fax: ''

})

const rules = {
    location: {required, minLength: minLength(10)},
    website: {required},
    email: {required, email},
    phone: {required, minLength: minLength(9)},
    fax: {numeric, minLength: minLength(9)}
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
        axios.post(`${serverURL}/createOrUpdateSchoolContact`, sendContactDataBody).then(res => {
            console.log('hahaha ok from server')
            console.log(res.data)
            editMode.value= false
        })

    } else {
        console.log('not sending mate, there is error')
    }
}

const handleToggleEditContactForm = ()=> {
    editMode.value = !editMode.value
}

onBeforeMount(() => {
    let contactRequestBody = {
        school_id: props.schoolId
    }
    axios.post(`${serverURL}/fetchSchoolContact`, contactRequestBody).then(res => {
        console.log(res.data)
        if (res.data.status === 200) {
            const parsedData = JSON.parse(res.data.school_contact)
            state.location = parsedData.location || ""
            state.website = parsedData.website || ""
            state.email = parsedData.email || ""
            state.phone = parsedData.phone || ""
            state.fax = parsedData.fax || ""

            console.log(parsedData)
        } else {
            editMode.value= true
            console.log('schoolDataNotFound')
        }
    }).catch(err => {
        console.log()
    })
})

const mapOptions = {
    center: {
        lat: props.schoolLocation.lat,
        lng: props.schoolLocation.lng
    },
    zoom: 17,
    options : {
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
    <div class="SchoolContactSubPageContainer text-genericDark py-2 px-10 flex gap-10 flex-row">
        <div class="contactEditAndDisplayContainer flex flex-col w-1/2">
            <div class="contactSubPageTitle text-xl font-semibold ">
                Contact details
            </div>
            <div
                v-if="editMode && props.currentUserCanEdit"
                class="contactForm flex flex-col"
            >
                <div class="contactFormTitle text-xl font-semibold mb-4">
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
                v-else
                class="displayContact pt-8 p-4 flex flex-col border-2 rounded border-blue-800"
            >
                <template
                    v-for="(value,key) in state"
                >
                    <div class="flex flex-row my-4">
                        <div class="flex w-1/3 text-xl font-semibold justify-end mr-4">
                            {{ key.toUpperCase() }}
                        </div>
                        <div class="flex w-2/3 text-lg">
                            {{ value }}
                        </div>
                    </div>
                </template>
            </div>
            <GenericButton
                v-if="props.currentUserCanEdit"
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
                    :options="{ position: {lat: props.schoolLocation.lat, lng: props.schoolLocation.lng} }"
                />
            </GoogleMap>
        </div>
    </div>
</template>
