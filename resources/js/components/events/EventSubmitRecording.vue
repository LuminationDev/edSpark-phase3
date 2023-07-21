<script setup>

import {computed, onMounted, ref} from "vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import {serverURL} from "@/js/constants/serverUrl";

const props = defineProps({
    eventId: {
        type: Number,
        required: true
    },
    currentUserIsOwner:{
        type: Boolean,
        required: true
    }
})

const linkInput = ref('')
const linkError = ref('')
const recordingLink = ref('')
const tempLink = ref('')

const {currentUser} = storeToRefs(useUserStore())

const handleSubmitLinkButton = async () => {
    console.log('click')
    console.log(linkInput.value)
    let recordingData = {
        user_id: currentUser.value.id,
        event_id: props.eventId,
        recording_link: linkInput.value
    }
    axios.post(`${serverURL}/addEventRecording`,recordingData).then(res => {
        console.log(res.data)
        recordingLink.value = linkInput.value
        tempLink.value = ''
    }).catch(err => {
        linkError.value = 'Failed to insert your recording. Try again'
    })

}

onMounted(() => {
    axios.get(`${serverURL}/checkEventRecording/${props.eventId}`).then(res => {
        if (res.data['event_recording']) {
            recordingLink.value = res.data['event_recording']
        }
    }).catch(err =>{
        console.log(err.message)
    })
})

const formattedRecordingLink = computed(() =>{
    if(!recordingLink.value.includes('http')){
        return 'https://' + recordingLink.value
    } else{
        return recordingLink.value
    }
})

const toggleEditLink = () =>{
    tempLink.value = recordingLink.value
    recordingLink.value = ''
}

const handleCancelEditLink = () => {
    recordingLink.value = tempLink.value
    tempLink.value = ''
}
</script>

<template>
    <div class="submitLink font-bold uppercase text-xl">
        The event has ended. We hoped it was a success.
    </div>
    <template v-if="!recordingLink && props.currentUserIsOwner">
        <div class="submitLinkbody text-base mt-4">
            You can share the link to the recording for others to watch
        </div>
        <label
            for="eventRecordingLink"
            class="mt-4"
        > Recording Link</label>
        <input
            v-model="linkInput"
            name="eventRecordingLink"
            type="text"
            class="py-2 px-4 mt-2 text-black"
            placeholder="Paste the link to the recording to share with other visitors"
        >
        <div class="flex flex-row mt-4">
            <button
                class="bg-main-teal flex w-fit  py-2 px-6 rounded-sm"
                @click="handleSubmitLinkButton"
            >
                Submit Link
            </button>
            <button
                v-if="currentUserIsOwner"
                class="bg-red-500 flex w-fit ml-4 py-2 px-6 rounded-sm"
                @click="handleCancelEditLink"
            >
                Cancel Edit
            </button>
            <div
                v-if="linkError"
                class="error-message flex text-base ml-6 text-red-500 text-center items-center justify-center"
            >
                {{ linkError }}
            </div>
        </div>
    </template>
    <template v-else-if="!!recordingLink">
        <div class="submitLinkbody text-base mt-4">
            Here is the recording link of the event shared by the organizer
        </div>
        <a
            class="cursor-pointer font-semibold hover:text-main-teal"
            :href="formattedRecordingLink"
        >
            {{ recordingLink }}
        </a>
        <div class="flex flex-row">
            <button
                v-if="currentUserIsOwner"
                class="bg-main-teal flex w-fit mt-2 py-2 px-6 rounded-sm"
                @click="toggleEditLink"
            >
                Edit Link
            </button>
        </div>
    </template>
    <template v-else>
        <div class="submitLinkbody text-base mt-4">
            There is no event recording shared. stay tuned
        </div>
    </template>
</template>

