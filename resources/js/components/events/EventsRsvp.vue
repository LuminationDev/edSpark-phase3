<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
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
import EventSubmitRecording from "@/js/components/events/EventSubmitRecording.vue";


const props = defineProps({
    locationType: {
        type: String,
        required: true
    },
    eventId: {
        type: Number,
        required: true
    },
    authorInfo: {
        type: Object,
        required: true
    },
    eventStartDate: {
        type: String,
        required: false,
        default: ''
    },
    eventEndDate: {
        type: String,
        required: false,
        default: ''
    }
})


const allSites = ref([])
const currentUserIsOwner = ref(false)
const currentOwnerInfo = ref({})
const currentUserRsvped = ref(false)
const currentRsvpInfo = ref({})
const editingRsvp = ref(false)
const rsvpError = ref('')
const schoolDropdownDisplay = ref('')

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const state = reactive({
    fullName: userStore.getUserFullName,
    schoolName: userStore.getUserSiteName,
    numOfGuest: "",
})

const rules = {
    fullName: {required},
    schoolName: {required},
    numOfGuest: {required, numeric},
}
const v$ = useVuelidate(rules, state)


axios.get(API_ENDPOINTS.SCHOOL.FETCH_ALL_SITES).then(res => {
    allSites.value = res.data
}).catch(err => {
    console.error(err);
    console.log('Theres an error');
})

onMounted(() => {
    let checkRsvpData = {
        event_id: props.eventId,
        user_id: currentUser.value.id
    }
    console.log(currentUser)

    // check if current user is Rsvped or Owner
    axios.post(API_ENDPOINTS.EVENT.CHECK_IF_USER_RSVPED, checkRsvpData).then(res => {
        console.log(res.data)
        currentUserIsOwner.value = res.data['isOwner'] === 'true'
        if (currentUserIsOwner.value) {
            currentOwnerInfo.value = res.data['owner_info']
        }
        currentUserRsvped.value = res.data['rsvped'] === 'true'
        currentRsvpInfo.value = res.data['rsvp_info']

    })
    v$.value.schoolName.$dirty = false
    console.log(eventStatus.value)
})

// ENDED, RUNNING, SCHEDULED
const eventStatus = computed(() => {
    const currentDate = Date.now()
    const eventStartDate = Date.parse(props.eventStartDate)
    const eventEndDate = Date.parse(props.eventEndDate)
    if (currentDate > eventEndDate) {
        return "ENDED"
    } else if (eventStartDate < currentDate && currentDate < eventEndDate) {
        return "RUNNING"
    } else if (currentDate < eventStartDate) {
        return "SCHEDULED"
    } else {
        return "UNKNOWN"
    }


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
    // if statement to prevent SearchDropdown to init name to empty object upon creation
    if (data.name) {
        v$.value.schoolName.$model = data.name

    }
}

const handleSubmitRsvp = () => {
    v$.value.$validate();
    if (!!v$.value.$errors) {
        console.log('successfully validated')
        rsvpError.value = ''
        const rsvpData = {
            user_id: currentUser.value.id,
            id: props.eventId,
            full_name: state.fullName,
            school_name: state.schoolName,
            number_of_guests: state.numOfGuest
        }
        return axios.post(API_ENDPOINTS.EVENT.ADD_RECORDING, rsvpData).then(res => {
            console.log(res.data)
            currentUserRsvped.value = true
            currentRsvpInfo.value = rsvpData
            editingRsvp.value = false
        }).catch(err => {
            console.log(err)
            currentUserRsvped.value = false
            rsvpError.value = err.message
        })


    } else {
        console.log('not sending mate, there is error')
        rsvpError.value = 'Please double check your details'

    }
}

const handleStartEditRsvp = () => {
    editingRsvp.value = true
    console.log(currentRsvpInfo.value['school_name'])
    v$.value.fullName.$model = currentRsvpInfo.value['full_name']
    v$.value.schoolName.$model = currentRsvpInfo.value['school_name']
    v$.value.numOfGuest.$model = currentRsvpInfo.value['number_of_guests']
    schoolDropdownDisplay.value = currentRsvpInfo.value['school_name']
}

const handleClickContactOrganiser = () => {
    console.log('Contacting Organiser!')

}

onMounted(() => {
    if (userStore.getUserSiteName) {
        console.log(userStore.getUserSiteName)
        v$.value.schoolName.$model = userStore.getUserSiteName
        schoolDropdownDisplay.value = userStore.getUserSiteName
    }
})
</script>

<template>
    <div class="EventRsvpFormContainer bg-blue-900 mt-5 px-4 py-4 text-white">
        <div class="font-bold rsvpHeader text-2xl uppercase">
            Register for this event
        </div>

        <div class="border-b-2 border-b-white border-dashed flex flex-col py-2 rsvpSubheader text-lg">
            <div class="eventTypeDescriptor pb-4">
                This event is <strong> {{ props.locationType }} </strong>
                {{ props.locationType.toLowerCase() !== "hybrid" ? "only" : '' }}
            </div>
            <div class="eventRsvp form-cta pb-4">
                Please register your interest below to reserve your spot!
            </div>
        </div>
        <div
            v-if="currentUserIsOwner"
            class="border-b-2 border-dashed border-white flex flex-col gap-2 py-4"
        >
            <div class="font-bold rsvpHeader text-xl uppercase">
                You are the owner of this event
            </div>
            <div>
                Number of guests registered: {{ currentOwnerInfo['total_guest'] }}
            </div>
        </div>
        <form
            v-else-if="(!currentUserIsOwner && !currentUserRsvped && eventStatus !== 'ENDED') || editingRsvp"
            class="border-b-2 border-dashed border-white flex flex-col gap-2 py-4 rsvpFormInputs"
            autocomplete="off"
            @submit.prevent=""
        >
            <TextInput
                v-model="v$.fullName.$model"
                field-id="fullName"
                :v$="v$.fullName"
                placeholder="Full name"
            >
                <template #label>
                    Full name
                </template>
            </TextInput>

            <TextInput
                v-model="v$.numOfGuest.$model"
                field-id="numOfGuest"
                :v$="v$.numOfGuest"
                placeholder="Enter number of guest"
            >
                <template #label>
                    Number of guests
                </template>
            </TextInput>

            <div class="-mt-1 Selector dropdown flex flex-col school">
                <label class="-mb-2 ml-2"> School name (start typing your school name and click on the result)</label>
                <SearchDropdown
                    class="-mt-2 searchable_dropdown"
                    :options="dropdownSites"
                    :placeholder=" schoolDropdownDisplay ||'Type and select your school'"
                    name="site"
                    :close-on-outside-click="true"
                    @selected="onSelectedSchoolDropdown"
                />
                <ErrorMessages
                    :v$="v$.schoolName"
                />
            </div>
            <div class="flex items-center flex-row">
                <GenericButton
                    :callback="handleSubmitRsvp"
                    class="!bg-rose-400 font-semibold mt-4 px-6 rounded-sm w-fit"
                >
                    <template #default>
                        RSVP
                    </template>
                </GenericButton>
                <span
                    v-if="rsvpError"
                    class="cursor-pointer font-semibold mt-4 px-4 text-red-500"
                    @click="rsvpError = ''"
                > {{ rsvpError }}</span>
            </div>
        </form>

        <EventRsvpSummary
            v-else-if="!currentUserIsOwner && eventStatus !== 'ENDED'"
            :rsvp-info="currentRsvpInfo"
            @start-edit-rsvp="handleStartEditRsvp"
        />
        <div
            v-else
            class="eventRsvpClosed"
        >
            <div class="font-bold mt-4 rsvpHeader text-xl uppercase">
                This event has ended and registration has closed. Thank you
            </div>
        </div>
        <!--   Event Contact Form     -->
        <div
            v-if="(eventStatus === 'ENDED') "
            class="border-b-2 border-b-white border-dashed flex flex-col py-4 text-lg"
        >
            <EventSubmitRecording
                :event-id="props.eventId"
                :current-user-is-owner="currentUserIsOwner"
            />
        </div>
        <div
            v-else
            class="border-b-2 border-b-white border-dashed contactHeader eventContactForm flex flex-col py-4 text-lg"
        >
            <div class="font-bold rsvpHeader text-xl uppercase">
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
                    class="!bg-main-teal font-semibold mt-4 px-6 rounded-sm w-fit"
                >
                    <template #default>
                        Email organiser
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