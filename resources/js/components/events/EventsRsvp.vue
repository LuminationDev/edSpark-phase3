<script setup>
import {ref, computed, reactive} from 'vue'
import {email, minLength, numeric, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import {serverURL} from "@/js/constants/serverUrl";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";

const props = defineProps({
    locationType:{
        type: String,
        required: true
    }
})

const emits = defineEmits([])

const state = reactive({
    firstName:"",
    lastName: "",
    email:'',
    phone: "",
    fax: ''

})

const rules = {
    firstName: {required},
    lastName: {required},
    schoolName: {required},
    numOfGuest: {required, numeric},
}

const v$ = useVuelidate(rules, state)

const handleSubmitRsvp = () => {
    v$.value.$validate();
    if (!v$.$errors) {
        console.log('successfully validated')
        // console.log(JSON.stringify(state))
        // let sendContactDataBody = {
        //     school_id: props.schoolId,
        //     school_contact: JSON.stringify(state)
        // }
        // axios.post(`${serverURL}/createOrUpdateSchoolContact`, sendContactDataBody).then(res => {
        //     console.log('hahaha ok from server')
        //     console.log(res.data)
        // })

    } else {
        console.log('not sending mate, there is error')
    }
}

const handleClickContactOrganiser = () =>{
    console.log('Contacting Organiser!')
}
</script>

<template>
    <div class="EventRsvpFormContainer bg-blue-900 mt-5 px-4 py-4 text-white">
        <div class="rsvpHeader font-bold uppercase text-2xl">
            Register for this event
        </div>

        <div class="rsvpSubheader  flex flex-col py-2 text-lg border-b-2 border-b-white border-dashed">
            <div class="eventTypeDescriptor pb-4">
                This event is <strong> {{ props.locationType }} </strong> only
            </div>
            <div class="eventRsvp form-cta pb-4">
                Please register your interest below to reserve your spot!
            </div>
        </div>

        <div
            class="rsvpFormInputs flex flex-col gap-2 py-4 border-b-2 border-white border-dashed "
        >
            <TextInput
                v-model="v$.firstName.$model"
                field-id="firstName"
                :v$="v$.firstName"
                placeholder="First name"
            >
                <template #label>
                    First name
                </template>
            </TextInput>
            <TextInput
                v-model="v$.lastName.$model"
                field-id="lastName"
                :v$="v$.lastName"
                placeholder="Last name"
            >
                <template #label>
                    Last name
                </template>
            </TextInput>
            <TextInput
                v-model="v$.schoolName.$model"
                field-id="schoolName"
                :v$="v$.schoolName"
                placeholder="School name"
            >
                <template #label>
                    School Name
                </template>
            </TextInput>
            <TextInput
                v-model="v$.numOfGuest.$model"
                field-id="numOfGuest"
                :v$="v$.numOfGuest"
                placeholder="Enter number of guest"
            >
                <template #label>
                    Number of Guest.
                </template>
            </TextInput>
            <GenericButton
                :callback="handleSubmitRsvp"
                class="mt-4 rounded-sm !bg-rose-400 w-fit px-6 font-semibold"
            >
                <template #default>
                    RSVP
                </template>
            </GenericButton>
        </div>
        <!--   Event Contact Form     -->
        <div class="eventContactForm contactHeader  flex flex-col py-2 text-lg border-b-2 border-b-white border-dashed">
            <div class="rsvpHeader font-bold uppercase text-2xl">
                Contact the organiser
            </div>
            <div class="eventRsvp form-cta pb-4">
                Do you have any question? Reach out to the organiser for more information.
            </div>

            <GenericButton
                :callback="handleClickContactOrganiser"
                class="mt-4 rounded-sm !bg-main-teal w-fit px-6 font-semibold"
            >
                <template #default>
                    Contact
                </template>
            </GenericButton>
        </div>
    </div>
</template>
