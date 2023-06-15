<script setup>

import TextInput from "@/js/components/bases/TextInput.vue";
import {reactive} from "vue";
import { required, minLength, requiredIf, email, url, numeric } from '@vuelidate/validators'
import useVuelidate from '@vuelidate/core'
import GenericButton from "@/js/components/button/GenericButton.vue";

const props = defineProps({

})

const state = reactive({
    location:"",
    website: "",
    email:'',
    phone: "",
})

const rules = {
    location:{required, minLength: minLength(10)},
    website:{required, url},
    email:{required, email},
    phone:{required, numeric, minLength: minLength(9) }
}

const v$ = useVuelidate(rules,state)

const handleSaveContactForm = () => {
    v$.value.$validate();
    console.log(v$.value)
}

</script>

<template>
    <div class="SchoolContactSubPageContainer text-genericDark py-2 px-10">
        <div class="contactForm w-1/3">
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
            <GenericButton
                :callback="handleSaveContactForm"
                custom-class="mt-4"
            >
                <template #default>
                    Submit Contact Information
                </template>
            </GenericButton>
        </div>
        <div class="displayContact w-1/3 mt-16 flex flex-col border-2 rounded border-blue-800">
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
    </div>
</template>
