<script setup>


import {simpleValidateUrl} from "@/js/helpers/stringHelpers";

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

const emits = defineEmits(['sendEmptyErrorMessage', 'sendNewLink', 'sendUrlFromServerInvalid'])

const handleClickErrorMessage = () => {
    console.log('inside ownerems link')
    emits('sendEmptyErrorMessage')
}

const formattedLink = (userLink) => {
    console.log("Original Link:", userLink);
    if(!simpleValidateUrl(props.currentUserEMSLink)){
        console.log('url is not valid')
        emits('sendUrlFromServerInvalid')
        return
    }
    if (!userLink.includes("http://") && !userLink.startsWith('http://')) {

        return "http://" + userLink;
    } else {

        return userLink;
    }
}


</script>

<template>
    <form
        class="border-b-2 border-white flex flex-col gap-2 py-8 rsvpFormInputs"
        autocomplete="off"
        @submit.prevent=""
    >
        <div class="-mt-1 Selector dropdown flex flex-col school">
            <label class="-mb-6">
                Please register for this event through the link below<br><br>
                <span class="-mt-2 hover:text-main-lightTeal flex font-bold mb-4 text-xl">
                    <a
                        :href="formattedLink(props.currentUserEMSLink)"
                        target="_blank"
                    >
                        {{ props.currentUserEMSLink }}
                    </a>
                </span>
            </label>
        </div>
        <div class="flex items-center flex-row">
            <span
                v-if="props.errorMessage"
                class="cursor-pointer font-semibold mt-6 px-4 text-red-500"
                @click="handleClickErrorMessage"
            >
                {{ props.errorMessage }}
            </span>
        </div>
    </form>
</template>
