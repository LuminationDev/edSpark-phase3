<script setup>
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {storeToRefs} from "pinia";
import {computed, onMounted, reactive, ref} from 'vue'

import GenericButton from "@/js/components/button/GenericButton.vue";
import EventEMSNoOwnerEMSLink from "@/js/components/events/EMSForms/EventEMSNoOwnerEMSLink.vue";
import EventEMSNoOwnerNoEMSLink from "@/js/components/events/EMSForms/EventEMSNoOwnerNoEMSLink.vue";
import EventEMSOwnerEMSLink from "@/js/components/events/EMSForms/EventEMSOwnerEMSLink.vue";
import EventEMSOwnerNoEMSLink from "@/js/components/events/EMSForms/EventEMSOwnerNoEMSLink.vue";
import EventSubmitRecording from "@/js/components/events/EventSubmitRecording.vue";
import {useUserStore} from "@/js/stores/useUserStore";

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

const state = reactive({
    currentUserEMSLink: ''
})

const rules = {
    currentUserEMSLink: {required}
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


const handleSubmitRsvp = () => {
    console.log('Submit RSVP is called')

}


const handleClickContactOrganiser = () => {
    console.log('Contacting Organiser!')
}

const handleEmptyErrorMessage = () => {
    console.log('this is the function handle empty error message called inside parent')
    rsvpError.value = ''
}

</script>

<template>
    <div class="EventRsvpFormContainer bg-blue-900 mt-5 px-4 py-4 text-white">
        <div class="font-bold rsvpHeader text-2xl">
            Register for this event
        </div>

        <div class="border-b-2 border-b-white flex flex-col py-2 rsvpSubheader text-lg">
            <div class="eventTypeDescriptor pb-2 pt-4">
                This event is <span class="font-semibold uppercase"> {{ props.locationType }} </span>
                {{ props.locationType ? (props.locationType.toLowerCase() !== "hybrid" ? "only" : '') : "" }}
            </div>
            <div class="eventRsvp form-cta pb-4">
                Please register your interest below to reserve your spot!
            </div>
        </div>

        <!--    Form no 1 - conditional, user=owner && EMS=yes-->
        <template
            v-if="(currentUserIsOwner && currentUserHasProvidedEMSLink && eventStatus !== 'ENDED') || editingEMSlink"
        >
            <EventEMSOwnerEMSLink
                :current-user-e-m-s-link="v$.currentUserEMSLink.$model"
                :button-callback="handleSubmitRsvp"
                :error-message="rsvpError"
                :v$="v$.currentUserEMSLink"
                @send-empty-error-message="handleEmptyErrorMessage"
            />
        </template>
        <!--        Fo>rm no 2 - conditional, user=owner && EMS=No-->
        <template
            v-else-if="(currentUserIsOwner && !currentUserHasProvidedEMSLink && eventStatus !== 'ENDED') || editingEMSlink"
        >
            <EventEMSOwnerNoEMSLink
                :current-user-e-m-s-link="v$.currentUserEMSLink.$model"
                :button-callback="handleSubmitRsvp"
                :error-message="rsvpError"
                :v$="v$.currentUserEMSLink"
                @send-empty-error-message="handleEmptyErrorMessage"
            />
        </template>
        <!--         Form no 3 - conditional, user=Not-owner && EMS=Yes-->
        <template
            v-else-if="(!currentUserIsOwner && eventStatus !== 'ENDED') || editingEMSlink"
        >
            <EventEMSNoOwnerEMSLink
                :current-user-e-m-s-link="v$.currentUserEMSLink.$model"
            />
        </template>
        <!--        &lt;!&ndash;        Form no 4 - conditional, user=Not-owner && EMS=No&ndash;&gt;-->
        <!--        <template-->
        <!--            v-else-if="(!currentUserIsOwner && !currentUserHasProvidedEMSLink && eventStatus !== 'ENDED') || editingEMSlink"-->
        <!--        >-->
        <!--            <EventEMSNoOwnerNoEMSLink-->
        <!--                :current-user-e-m-s-link="v$.currentUserEMSLink.$model"-->
        <!--                :button-callback="handleSubmitRsvp"-->
        <!--                :error-message="rsvpError"-->
        <!--                :v$="v$.currentUserEMSLink"-->
        <!--                @send-empty-error-message="handleEmptyErrorMessage"-->
        <!--            />-->
        <!--        </template>-->

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
