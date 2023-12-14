<script setup>
import {storeToRefs} from "pinia";
import {ref} from "vue";

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
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

const emits = defineEmits(['sendEmptyErrorMessage'])
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
            <label class="-mb-2"> You have provided the
                Link<br>
                <span class="flex font-bold mt-4 text-xl">
                    {{ props.currentUserEMSLink }}
                </span>
            </label>

            <ErrorMessages
                :v$="v$"
            />
        </div>
        <div class="flex items-center flex-row">
            <GenericButton
                id="rsvpBtn"
                :callback="props.buttonCallback"
                class="!text-white !w-28 font-bold mt-2 px-6 rounded-sm text-lg w-fit"
            >
                <template #default>
                    Edit
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
