<script setup>
import {ref, computed, reactive, onMounted} from 'vue'
import {email, minLength, numeric, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";
import axios from "axios";
import {serverURL} from "@/js/constants/serverUrl";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import SearchDropdown from 'search-dropdown-vue';
import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import EventRsvpSummary from "@/js/components/events/EventRsvpSummary.vue";


const props = defineProps({
    locationType: {
        type: String,
        required: true
    },
    eventId:{
        type: Number,
        required: true
    },
    authorInfo:{
        type: Object,
        required: true
    }
})

const allSites = ref([])
const currentUserIsOwner = ref(false)
const currentOwnerInfo = ref({})
const currentUserRsvped = ref(false)
const currentRsvpInfo = ref({})
const rsvpError = ref('')



const {currentUser} = storeToRefs(useUserStore())

const state = reactive({
    firstName: "",
    lastName: "",
    schoolName: '',
    numOfGuest: "",
})

const rules = {
    firstName: {required},
    lastName: {required},
    schoolName: {required},
    numOfGuest: {required, numeric},
}
const v$ = useVuelidate(rules, state)



axios.get(`${serverURL}/fetchAllSites`).then(res => {
    allSites.value = res.data
}).catch(err => {
    console.error(err);
    console.log('Theres an error');
})

onMounted(() =>{
    let checkRsvpData = {
        event_id: props.eventId,
        user_id: currentUser.value.id
    }
    console.log(currentUser)

    // check if current user is Rsvped or Owner
    axios.post(`${serverURL}/checkIfUserRsvped`, checkRsvpData).then(res => {
        console.log(res.data)
        currentUserIsOwner.value = res.data['isOwner'] === 'true'
        if(currentUserIsOwner.value){
            currentOwnerInfo.value = res.data['owner_info']
        }
        currentUserRsvped.value = res.data['rsvped'] === 'true'
        currentRsvpInfo.value = res.data['rsvp_info']

    })
    v$.value.schoolName.$dirty = false
})


const dropdownSites = computed(() => {
    if (allSites.value.length === 0) return []
    else {
        return allSites.value.filter(site => ['SCHL', 'PRESC'].includes(site['category_code'])).map(site => {
            return {id: site.site_id, name: site.site_name}
        })
    }
})

const onSelectedSchoolDropdown = (data) => {
    v$.value.schoolName.$model = data.name
}

const handleSubmitRsvp = () => {
    v$.value.$validate();
    if (!!v$.value.$errors) {
        console.log('successfully validated')
        rsvpError.value = ''
        const rsvpData = {
            user_id: currentUser.value.id,
            event_id: props.eventId,
            full_name: state.firstName +  " " + state.lastName,
            school_name: state.schoolName,
            number_of_guests: state.numOfGuest
        }
        return axios.post(`${serverURL}/addRsvpToEvent`, rsvpData).then(res => {
            console.log(res.data)
            currentUserRsvped.value = true
            currentRsvpInfo.value = rsvpData
        }).catch(err =>{
            console.log(err)
            currentUserRsvped.value = false
            rsvpError.value = 'Failed to RSVP event. Please contact the organizer below'
        })


    } else {
        console.log('not sending mate, there is error')
        rsvpError.value = 'Please double check your details'

    }
}

const handleClickContactOrganiser = () => {
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
            v-if="currentUserIsOwner"
            class="flex flex-col gap-2 py-4 border-b-2 border-white border-dashed"
        >
            <div class="rsvpHeader font-bold uppercase text-xl">
                You are the owner of this event
            </div>
            <div> Number of guests registered: {{ currentOwnerInfo['total_guest'] }} </div>
        </div>
        <form
            v-else-if="!currentUserIsOwner && !currentUserRsvped"
            class="rsvpFormInputs flex flex-col gap-2 py-4 border-b-2 border-white border-dashed "
            autocomplete="off"
            @submit.prevent=""
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
                v-model="v$.numOfGuest.$model"
                field-id="numOfGuest"
                :v$="v$.numOfGuest"
                placeholder="Enter number of guest"
            >
                <template #label>
                    Number of Guest.
                </template>
            </TextInput>

            <div class="school dropdown Selector flex flex-col -mt-1">
                <label class="-mb-2 ml-2"> School Name</label>
                <SearchDropdown
                    class="searchable_dropdown -mt-2"
                    :options="dropdownSites"
                    placeholder="Type and select your school"
                    name="site"
                    :close-on-outside-click="true"
                    @selected="onSelectedSchoolDropdown"
                />
                <ErrorMessages
                    :v$="v$.schoolName"
                />
            </div>
            <div class="flex flex-row items-center">
                <GenericButton
                    :callback="handleSubmitRsvp"
                    class="mt-4 rounded-sm !bg-rose-400 w-fit px-6 font-semibold"
                >
                    <template #default>
                        RSVP
                    </template>
                </GenericButton>
                <span
                    v-if="rsvpError"
                    class="text-red-500 font-semibold mt-4 px-4 cursor-pointer"
                    @click="rsvpError = ''"
                > {{ rsvpError }}</span>
            </div>
        </form>
        <EventRsvpSummary
            v-else
            :rsvp-info="currentRsvpInfo"
        />
        <!--   Event Contact Form     -->
        <div class="eventContactForm contactHeader  flex flex-col py-4 text-lg border-b-2 border-b-white border-dashed">
            <div class="rsvpHeader font-bold uppercase text-xl">
                Contact the organiser
            </div>
            <div class="eventRsvp form-cta pb-4">
                Do you have any question? Reach out to the organiser for more information.
            </div>
            <a
                v-if="props.authorInfo && props.authorInfo['author_email']"
                class="flex"
                :href="`mailto:${props.authorInfo['author_email']}`"
            >
                <GenericButton
                    :callback="handleClickContactOrganiser"
                    class="mt-4 rounded-sm !bg-main-teal w-fit px-6 font-semibold"
                >
                    <template #default>
                        Contact
                    </template>
                </GenericButton>

            </a>
        </div>
    </div>
</template>

<style scoped>
.searchable_dropdown :deep(.dropdown-toggle input) {
    padding: 8px !important;
    border-radius: 0.25rem;
}
</style>