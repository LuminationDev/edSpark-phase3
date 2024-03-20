<script setup>
import useVuelidate from "@vuelidate/core";
import {required, url} from "@vuelidate/validators";
import {computed, onMounted, reactive, ref} from 'vue'
import {useRoute} from "vue-router";

import GenericButton from "@/js/components/button/GenericButton.vue";
import EventEMSNoOwnerEMSLink from "@/js/components/events/EMSForms/EventEMSNoOwnerEMSLink.vue";
import EventEMSNoOwnerNoEMSLink from "@/js/components/events/EMSForms/EventEMSNoOwnerNoEMSLink.vue";
import EventEMSOwnerEMSLink from "@/js/components/events/EMSForms/EventEMSOwnerEMSLink.vue";
import EventEMSOwnerNoEMSLink from "@/js/components/events/EMSForms/EventEMSOwnerNoEMSLink.vue";
import EventSubmitRecording from "@/js/components/events/EventSubmitRecording.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {simpleValidateUrl} from "@/js/helpers/stringHelpers";

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

const currentUserIsOwner = ref(false)
const currentUserHasProvidedEMSLink = ref(false)
const editingEMSlink = ref(false)
const rsvpError = ref('')
const route = useRoute()

const state = reactive({
    currentUserEMSLink: ''
})

const isLoading = ref(false)
const tempLink = ref('')

const rules = {
    currentUserEMSLink: {required, url}
}
const v$ = useVuelidate(rules, state)

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


const getEMSLink = () => {
    const urlWithEventID = `${API_ENDPOINTS.EVENT.FETCH_EMS_LINK}${route.params.id}`;
    axios.get(urlWithEventID)
        .then(res => {
            tempLink.value = res.data.data.ems_link;
            state.currentUserEMSLink = res.data.data.ems_link;
            //currentUserIsOwner.value = res.data.data.is_owner;
            currentUserIsOwner.value = Boolean(res.data.data.is_owner);
            currentUserHasProvidedEMSLink.value = true;
        })
        .catch(err => {
            rsvpError.value = err.message;
        });
};

onMounted(() => {
    getEMSLink()

})

const handleClickContactOrganiser = () => {
}

const handleEmptyErrorMessage = () => {
    rsvpError.value = ''
}
const handleClickEditLink = () => {
    editingEMSlink.value = true
}

const handleCancelLink = () => {
    editingEMSlink.value = false
    state.currentUserEMSLink = tempLink.value;
}


const handleClickSubmitLink = () => {
    if (!simpleValidateUrl(v$.value.currentUserEMSLink.$model)) {
        rsvpError.value = 'Please enter a valid URL'
        return;
    }
    isLoading.value = true;
    rsvpError.value = false
    tempLink.value = state.currentUserEMSLink

    const data = {
        event_id: route.params.id,
        ems_link: v$.value.currentUserEMSLink.$model
    };

    return axios.post(API_ENDPOINTS.EVENT.ADD_OR_EDIT_EMS_LINK, data)
        .then(res => {
            editingEMSlink.value = false;
        })
        .catch(err => {
            rsvpError.value = err.message;
        })
        .finally(() => {
            isLoading.value = false;
        });

};

const handleAcceptNewLink = (newlink) => {
    state.currentUserEMSLink = newlink
}
const handleInvalidUrlFromServer = () => {
    currentUserHasProvidedEMSLink.value = false
    state.currentUserEMSLink = ''
}
</script>

<template>
    <div class="EventRsvpFormContainer bg-secondary-blueberry mt-5 px-4 py-4 text-white">
        <div class="font-bold rsvpHeader text-2xl">
            Register for this event
        </div>

        <div class="border-b-2 border-b-white flex flex-col py-2 rsvpSubheader text-lg">
            <div
                v-if="props.locationType"
                class="eventTypeDescriptor pb-2 pt-4"
            >
                This event is <span class="font-semibold uppercase"> {{ props.locationType }} </span>
                {{ props.locationType ? (props.locationType.toLowerCase() !== "hybrid" ? "only" : '') : "" }}
            </div>
            <div class="eventRsvp form-cta pb-4">
                Please register your interest below to reserve your spot!
            </div>
        </div>

        <!--        Fo>rm no 2 - conditional, user = owner && EMS = No-->
        <template
            v-if="(currentUserIsOwner && !currentUserHasProvidedEMSLink && eventStatus !== 'ENDED') || editingEMSlink"
        >
            <EventEMSOwnerNoEMSLink
                :current-user-e-m-s-link="state.currentUserEMSLink"
                :button-callback="handleClickSubmitLink"
                :button-cancelback="handleCancelLink"
                :error-message="rsvpError"
                :v$="v$.currentUserEMSLink"
                :is-loading="isLoading"
                @send-new-link="handleAcceptNewLink"
                @send-empty-error-message="handleEmptyErrorMessage"
            />
        </template>

        <!--    Form no 1 - conditional, user=owner && EMS=yes-->
        <template
            v-else-if="(currentUserIsOwner && currentUserHasProvidedEMSLink && eventStatus !== 'ENDED') || editingEMSlink"
        >
            <EventEMSOwnerEMSLink
                :current-user-e-m-s-link="v$.currentUserEMSLink.$model"
                :button-callback="handleClickEditLink"
                :error-message="rsvpError"
                :v$="v$.currentUserEMSLink"
                @send-new-link="handleAcceptNewLink"
                @send-empty-error-message="handleEmptyErrorMessage"
            />
        </template>

        <!--    Form no 4 - conditional, user = no owner && EMS = yes-->
        <template
            v-else-if="(!currentUserIsOwner && currentUserHasProvidedEMSLink && eventStatus !== 'ENDED') || editingEMSlink"
        >
            <EventEMSNoOwnerEMSLink
                :current-user-e-m-s-link="state.currentUserEMSLink"
                :error-message="rsvpError"
                :v$="v$.currentUserEMSLink"
                @send-new-link="handleAcceptNewLink"
                @send-empty-error-message="handleEmptyErrorMessage"
                @send-url-from-server-invalid="handleInvalidUrlFromServer"
            />
        </template>

        <!--    Form no 3 - conditional, user = no owner && EMS = no-->
        <template
            v-else-if="(!currentUserIsOwner && !currentUserHasProvidedEMSLink && eventStatus !== 'ENDED') || editingEMSlink"
        >
            <EventEMSNoOwnerNoEMSLink />
        </template>
        <div
            v-if="(eventStatus === 'ENDED') "
            class="border-b-2 border-b-white flex flex-col py-4 text-lg"
        >
            <EventSubmitRecording
                :event-id="props.eventId"
                :current-user-is-owner="currentUserIsOwner"
            />
        </div>
        <div
            v-else
            class="contactHeader eventContactForm flex flex-col py-4 text-lg"
        >
            <div class="font-bold mb-4 rsvpHeader text-xl">
                Contact the organiser
            </div>
            <div class="eventRsvp form-cta pb-4">
                Do you have any question? Reach out to the organiser for more information.
            </div>
            <a
                v-if="props.authorInfo && props.authorInfo['author_email']"
                class="!no-underline flex"
                :href="`mailto:${props.authorInfo['author_email']}`"
            >
                <GenericButton
                    id="contactBtn"
                    :callback="handleClickContactOrganiser"
                    class="!bg-secondary-coolGrey !text-black font-semibold mt-4 px-6 rounded-sm w-fit"
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
    color: #d9dae3;
}
</style>
