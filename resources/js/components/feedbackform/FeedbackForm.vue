<script setup lang="ts">
import {useVuelidate} from "@vuelidate/core";
import {email, maxLength, required} from "@vuelidate/validators";
import axios from "axios";
import {storeToRefs} from "pinia";
import {reactive, ref, watch} from "vue";
import {useRouter} from "vue-router";

import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import ScreenshotInfoPopup from "@/js/components/feedbackform/ScreenshotInfoPopup.vue";
import FeedbackBackground from "@/js/components/svg/FeedbackIcon/FeedbackBackground.vue";
import InfoCircleIcon from "@/js/components/svg/InfoCircleIcon.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";
import {simpleValidateUrl} from "@/js/helpers/stringHelpers";
import CustomErrorMessages from "@/js/components/feedbackform/CustomErrorMessages.vue";

const router = useRouter()
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const isLoading = ref(false)
const showFeedbackForm = ref(false)
const feedbackError = ref("")
const screenShotInfoPopUp = ref(false)
const displayError = ref('')
const foundError = ref(false)

// use route.params.id (to get params)
const state = reactive({
    name: currentUser.value.full_name,
    email: currentUser.value.email,
    organisation: currentUser.value.site.site_name,
    urlIssue: '',
    content: ''
})

const rules = {
    name: {required},
    email: {
        required, email,
        maxLength: maxLength(255)
    },
    organisation: {required},
    urlIssue: {required, maxLength: maxLength(2083)},
    content: {required}
}

const v$ = useVuelidate(rules, state)

const emits = defineEmits(['emitFormOpenState', 'emitHideFeedbackForm'])

const handleTinyRichContent = (data) => {

    v$.value.content.$model = data
}

// function to validate the form and submit the data to backend using POST
const handleSubmitForm = async () => {
    if (!simpleValidateUrl(v$.value.urlIssue.$model)) {

        foundError.value = true
        displayError.value = 'Valid URL is required'
        return
    }
    foundError.value = false
    if(foundError.value == false) {
        const fields_check = await v$.value.$validate()
        if (fields_check) {

            const data = {
                feedback_id: ' ',
                user_name: v$.value.name.$model,
                email: v$.value.email.$model,
                organisation_name: v$.value.organisation.$model,
                issue_url: v$.value.urlIssue.$model,
                content: v$.value.content.$model
            }

            return axios.post(API_ENDPOINTS.FEEDBACK.CREATE_FEEDBACK, data)
                .then(res => {
                })
                .catch(err => {
                    feedbackError.value = err.message;
                })
                .finally(() => {
                    isLoading.value = false;
                })
        }
    }
    else {

    }
}

const handleCancelForm = () => {
    showFeedbackForm.value = !showFeedbackForm.value
}

const toggleFeedbackForm = (): void => {
    showFeedbackForm.value = !showFeedbackForm.value
    emits('emitFormOpenState', showFeedbackForm.value)
}

const showScreenShotInfoPopUpup = () => {
    screenShotInfoPopUp.value = true
}

const hideScreeShotInfoPop = () => {
    screenShotInfoPopUp.value = false
}

watch(router.currentRoute, () => {
    v$.value.urlIssue.$model = window.location.href
})

</script>

<template>
    <div
        class="cursor-pointer fixed right-1 sm:right-3 bottom-10 mr-auto overflow-hidden shadow- z-40"
        @click="toggleFeedbackForm"
    >
        <div>
            <FeedbackBackground />
        </div>
    </div>

    <div
        v-if="showFeedbackForm"
        class="backdrop-blur blur-overlay fixed top-0 left-0 h-full w-full z-[60]"
        @click="toggleFeedbackForm(); hideScreeShotInfoPop();"
    />

    <div
        v-if="showFeedbackForm"
        id="BaseFormParent_1"
        class="
            HideScrollBar
            bg-white
            border-[1px]
            fixed
            top-24
            inset-x-0
            max-h-[80vh]
            mx-auto
            overflow-auto
            overflow-y-auto
            p-8
            rounded-2xl
            text-black
            w-[50vw]
            z-[70]
            "
        @click="hideScreeShotInfoPop"
    >
        <div class="Introduction formHeader">
            <div class="">
                <div class="font-semibold text-[36px]">
                    Feedback form
                </div>
                <div class="flex flex-col smallAutoSaveHeaderSection">
                    <slot name="formHeader" />
                </div>
            </div>
            <TextInput
                v-model="v$.name.$model"
                :v$="v$.name"
                class="my-2"
                placeholder="Enter your name"
                :with-no-left-margin="true"
            >
                <template #label>
                    Contact name
                </template>
            </TextInput>
            <div class="my-2">
                <TextInput
                    v-model="v$.email.$model"


                    :v$="v$.email"
                    class="my-2"
                    placeholder="Enter your email address"
                    :with-no-left-margin="true"
                >
                    <template #label>
                        Contact email address
                    </template>
                </TextInput>
            </div>
            <div class="my-2">
                <TextInput
                    v-model="v$.organisation.$model"
                    :v$="v$.organisation"
                    class="my-2"
                    placeholder="Enter the name of the school or organisation you work for"
                    :with-no-left-margin="true"
                >
                    <template #label>
                        Organisation Name
                    </template>
                </TextInput>
            </div>
            <div class="my-2">
                <TextInput
                    v-model="v$.urlIssue.$model"
                    :v$="v$.urlIssue"
                    class="my-2 placeholder:text-red-700"
                    placeholder="https://edspark.sa.edu/software"
                    :with-no-left-margin="true"
                >
                    <template #label>
                        URL of page with issue
                    </template>
                </TextInput>
                <div v-if="foundError">
                    <CustomErrorMessages :error-text="displayError" class="-mt-6"/>
                </div>
            </div>
            <div>
                <div class="flex items-center flex-row ml-2 mt-6 relative">
                    <label> Describe your issue</label>
                    <InfoCircleIcon
                        id="infobtn"
                        class="h-6 mb-2 ml-auto mr-4 w-6"
                        @mouseover="showScreenShotInfoPopUpup"
                    />
                    <ScreenshotInfoPopup
                        v-if="screenShotInfoPopUp"
                        class="HideScrollBar fixed top-28 right-32 z-[80]"
                        @mouseleave="hideScreeShotInfoPop"
                    />
                </div>
                <TinyMceRichTextInput
                    :src-content="v$.content.$model"
                    :v$="v$.content"
                    @emit-tiny-rich-content="handleTinyRichContent"
                />
            </div>
            <div class="flex flex-row ml-2 mt-6">
                <GenericButton
                    id="cancelBtn"
                    :callback="handleCancelForm"
                    class="
                        !bg-[#FFFFFF]
                        !h-16
                        !text-adminTeal
                        !text-xl
                        2xl:w-[15%]
                        border-[1px]
                        border-adminTeal
                        mr-auto
                        sm:w-[20%]
                        "
                >
                    <template #default>
                        Cancel
                    </template>
                </GenericButton>
                <GenericButton
                    id="submitBtn"
                    :callback="handleSubmitForm"
                    class="!h-16 !text-xl 2xl:w-[15%] ml-auto hover:!bg-adminTeal sm:w-[20%]"
                >
                    <template #default>
                        Submit
                    </template>
                </GenericButton>
            </div>
        </div>
    </div>
</template>

<style>
.HideScrollBar::-webkit-scrollbar {
    display: none;
}

</style>
