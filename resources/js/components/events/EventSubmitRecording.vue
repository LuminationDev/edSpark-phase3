<script setup>

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
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
    axios.post(API_ENDPOINTS.EVENT.ADD_EVENT_RECORDING,recordingData).then(res => {
        console.log(res.data)
        recordingLink.value = linkInput.value
        tempLink.value = ''
    }).catch(err => {
        linkError.value = 'Failed to insert your recording. Try again'
    })

}

onMounted(() => {
    axios.get(`${API_ENDPOINTS.EVENT.CHECK_EVENT_RECORDING}${props.eventId}`).then(res => {
        if (res.data['recording']) {
            recordingLink.value = res.data['recording']
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
    <div class="font-bold submitLink text-xl">
        The event has ended. We hoped it was a success.
    </div>
    <template v-if="!recordingLink && props.currentUserIsOwner">
        <div class="mt-4 submitLinkbody text-base">
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
            class="mt-2 px-4 py-2 text-black"
            placeholder="Paste the link to the recording to share with other visitors"
        >
        <div class="flex flex-row mt-4">
            <button
                class="bg-main-teal flex px-6 py-2 rounded-sm w-fit"
                @click="handleSubmitLinkButton"
            >
                Submit Link
            </button>
            <button
                v-if="currentUserIsOwner"
                class="bg-red-500 flex ml-4 px-6 py-2 rounded-sm w-fit"
                @click="handleCancelEditLink"
            >
                Cancel Edit
            </button>
            <div
                v-if="linkError"
                class="error-message flex justify-center items-center ml-6 text-base text-center text-red-500"
            >
                {{ linkError }}
            </div>
        </div>
    </template>
    <template v-else-if="!!recordingLink">
        <div class="mt-4 submitLinkbody text-base">
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
                class="bg-main-teal flex mt-2 px-6 py-2 rounded-sm w-fit"
                @click="toggleEditLink"
            >
                Edit Link
            </button>
        </div>
    </template>
    <template v-else>
        <div class="mt-4 submitLinkbody text-base">
            There is no event recording shared. stay tuned
        </div>
    </template>
</template>

