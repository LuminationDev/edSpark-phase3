<script setup>
import {ref, computed} from 'vue'

const props = defineProps({
    rsvpInfo:{
        type: Object,
        required: true
    }
})



</script>

<template>
    <div class="EventRsvpSummaryContainer">
        <div class="rsvpSubheader  flex flex-col py-2 text-lg border-b-2 border-b-white border-dashed">
            <div class="eventRsvp form-cta pb-4">
                You have <strong> registered </strong> for this event. We are looking forward to your attendance.
            </div>
            <div class="eventRsvp form-cta pb-4">
                Your registered details are:
                <ul
                    v-if="props.rsvpInfo"
                    class="list-none RsvpdetailListContainer ml-4 "
                >
                    <li v-if="props.rsvpInfo['full_name']">
                        Full name: <strong>{{ ' ' + props.rsvpInfo['full_name'] }} </strong>
                    </li>
                    <li v-if="props.rsvpInfo['school_name']">
                        School name: <strong>{{ ' ' + props.rsvpInfo['school_name'] }}</strong>
                    </li>
                    <li v-if="props.rsvpInfo['number_of_guests']">
                        No. of guests: <strong>{{ ' ' + props.rsvpInfo['number_of_guests'] }}</strong>
                    </li>
                </ul>
            </div>
            <div class="eventRsvp form-cta pb-4">
                Please contact the organiser with the information below if there is any changes.
            </div>
        </div>
    </div>
</template>
