<script setup>
import {ref, computed} from 'vue'
import GenericButton from "@/js/components/button/GenericButton.vue";

const props = defineProps({
    rsvpInfo:{
        type: Object,
        required: true
    }
})
const emits = defineEmits(['startEditRsvp'])

const handleClickEditRsvp = () =>{
    emits('startEditRsvp')
}


</script>

<template>
    <div class="EventRsvpSummaryContainer">
        <div class="border-b-2 border-b-white border-dashed flex flex-col py-2 rsvpSubheader text-lg">
            <div class="eventRsvp form-cta pb-4">
                You have <strong> registered </strong> for this event. We are looking forward to your attendance.
            </div>
            <div class="eventRsvp form-cta pb-4">
                Your registered details are:
                <ul
                    v-if="props.rsvpInfo"
                    class="RsvpdetailListContainer list-none ml-4"
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
                Please contact the organiser with the information below if there are any changes.
            </div>
            <GenericButton
                :callback="handleClickEditRsvp"
                class="!bg-rose-400 font-semibold mt-4 px-6 rounded-sm w-fit"
            >
                <template #default>
                    Edit RSVP
                </template>
            </GenericButton>
        </div>
    </div>
</template>
