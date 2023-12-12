<script setup>
import {storeToRefs} from "pinia";
import {ref} from "vue";

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    currentUserEMSLink: {
        type: String,
        required: true,
    },
    buttonCallback: {
        type: Function,
        required: false,
        default: () => {
        }
    },
    errorMessage: {
        type: String,
        required: true
    },
    v$: {
        type: Object,
        required: false,
        default: () => {
        }
    }
})

const localLink = ref(props.currentUserEMSLink)
const emits = defineEmits(['sendEmptyErrorMessage', 'sendNewLink', 'sendSomething'])
const handleSendNewLink = () => {
    emits('sendNewLink', localLink.value)
}
const handleClickErrorMessage = () => {
    console.log('inside ownerems link')
    emits('sendEmptyErrorMessage')
}

const isLoading = ref(false)
</script>

<template>
    <form
        class="border-b-2 border-white flex flex-col gap-2 py-8 rsvpFormInputs"
        autocomplete="off"
        @submit.prevent=""
    >
        <div class="-mt-1 Selector dropdown flex flex-col school">
            <TextInput
                v-model="localLink"
                field-id="userSubmitLink"
                :v$="v$.currentUserEMSLink"
                placeholder="Enter the link here"
                :with-no-left-margin="true"
                @input-update="handleSendNewLink"
            >
                <template #label>
                    Provide link to your Event Management System
                </template>
            </TextInput>
            {{ rsvpError }}
            <GenericButton
                :callback="props.buttonCallback"
                class="!bg-secondary-coolGrey !text-black font-semibold mt-4 px-6 rounded-sm w-fit"
                :disabled="isLoading"
            >
                <template #default>
                    {{ isLoading ? 'Loading...' : 'Submit' }}
                </template>
            </GenericButton>
            <span
                v-if="props.errorMessage"
                class="cursor-pointer font-semibold mt-4 px-4 text-red-500"
                @click="handleClickErrorMessage"
            > {{ props.errorMessage }}</span>
        </div>
    </form>
</template>
