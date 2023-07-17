<script setup>
import {ref, reactive, onBeforeMount, computed} from "vue";
import {required, minLength, email, numeric} from '@vuelidate/validators'
import axios from "axios";
import useVuelidate from '@vuelidate/core'


import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {serverURL} from "@/js/constants/serverUrl";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import {useRoute} from "vue-router";

const props = defineProps({})

const editMode = ref(false)

const state = reactive({
    location: "",
    website: "",
    email: '',
    phone: "",
    fax: ''

})

const rules = {
    location: {minLength: minLength(10)},
    website: {},
    email: {email},
    phone: {minLength: minLength(9)},
    fax: {minLength: minLength(9)}
}
const v$ = useVuelidate(rules, state)
const {currentUser} = storeToRefs(useUserStore())
const route = useRoute()
const currentUserCanEdit = computed(() => {
    if (+route.params.id === currentUser.value.id) {
        return true
    } else {
        return false
    }
})
const handleSaveContactForm = () =>{
    console.log('called Save Partner Access/Contact Form --- TODO')
}

const handleToggleEditContactForm = () => {
    editMode.value = !editMode.value
}
const emits = defineEmits([])

</script>

<template>
    <div class="PartnerAccessSubPageContainer text-genericDark px-10 flex gap-10 flex-row">
        <div class="contactEditAndDisplayContainer flex flex-col w-1/2">
            <div class="contactSubPageTitle text-xl font-semibold ">
                Contact details
            </div>
            <div
                v-if="editMode && currentUserCanEdit"
                class="contactForm flex flex-col"
            >
                <div class="contactFormTitle text-xl font-semibold mb-4">
                    Partner access form
                </div>
                <TextInput
                    v-model="v$.location.$model"
                    field-id="partnerLocation"
                    :v$="v$.location"
                    placeholder="Enter Partner Address"
                >
                    <template #label>
                        Location
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.website.$model"
                    field-id="partnerWebsite"
                    :v$="v$.website"
                    placeholder="Enter Partner Website"
                >
                    <template #label>
                        Website
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.email.$model"
                    field-id="partnerEmail"
                    :v$="v$.email"
                    placeholder="Enter Partner Email Address"
                >
                    <template #label>
                        Email Address
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.phone.$model"
                    field-id="partnerPhone"
                    :v$="v$.phone"
                    placeholder="Enter Partner Phone Number"
                >
                    <template #label>
                        Phone Number
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.fax.$model"
                    field-id="partnerFax"
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
                class="displayContact pt-8 p-4 flex flex-col "
            >
                <template
                    v-for="(value,key) in state"
                    :key="key"
                >
                    <div class="flex flex-row my-4">
                        <div class="flex w-1/4 text-xl font-semibold justify-end mr-4">
                            {{ key.toUpperCase() }}
                        </div>
                        <div
                            class="flex flex-wrap w-3/4 text-lg"
                        >
                            <p class="text-ellipsis w-full break-words">
                                {{ value }}
                            </p>
                        </div>
                    </div>
                </template>
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
    </div>
</template>