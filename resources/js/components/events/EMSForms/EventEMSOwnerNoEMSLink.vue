<script setup>
import {storeToRefs} from "pinia";
import {ref} from "vue";

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Spinner from "@/js/components/spinner/Spinner.vue";
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
    buttonCancelback: {
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
    }, isLoading:
        {
            type: Boolean,
            required: true
        }
})

const localLink = ref(props.currentUserEMSLink)
const emits = defineEmits(['sendEmptyErrorMessage', 'sendNewLink', 'cancel-link'])
const handleSendNewLink = () => {
    emits('sendNewLink', localLink.value)
}
const handleClickErrorMessage = () => {
    console.log('inside ownerems link')
    emits('sendEmptyErrorMessage')
}

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
            <!--            <div-->
            <!--                v-if="props.isLoading"-->
            <!--                class="!w-28 bg-main-teal flex justify-center items-center spinnerContainer"-->
            <!--            >-->
            <!--                <Spinner />-->
            <!--            </div>-->
            <GenericButton
                :callback="props.buttonCallback"
                class="!text-white !w-28 font-bold px-6 rounded-sm text-lg w-fit"
                :disabled="props.isLoading"
            >
                <template #default>
                    <span>Submit</span>
                </template>
            </GenericButton>

            <GenericButton
                id="rsvpBtn"
                :callback="props.buttonCancelback"
                class="!text-white !w-28 font-bold mt-4 px-6 rounded-sm text-lg w-fit"
            >
                <template #default>
                    Cancel
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
