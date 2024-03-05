<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import axios from "axios";
import {storeToRefs} from "pinia";
import {emits} from "v-calendar/dist/types/src/use/datePicker";
import {computed, onMounted, reactive, Ref, ref} from "vue";
import {toast} from "vue3-toastify";

import ProfilePlaceholder from "@/assets/images/profilePlaceholder.webp";
import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CustomErrorMessages from "@/js/components/feedbackform/CustomErrorMessages.vue";
import SchoolImageChange from "@/js/components/schoolsingle/schoolContent/SchoolImageChange.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import Edit from "@/js/components/svg/Edit.vue";
import UserAvatarChange from "@/js/components/userprofile/userprofileupdate/UserAvatarChange.vue";
import UserCardItemSelector from "@/js/components/userprofile/userprofileupdate/UserCardItemSelector.vue";
import UserChecklistSelector from "@/js/components/userprofile/userprofileupdate/UserChecklistSelector.vue";
import {
    AvailableInterestsList,
    AvailableSchoolYearList,
    AvailableSubjectsList
} from "@/js/components/userprofile/userprofileupdate/userListing";
import UserProfileContentContainer from "@/js/components/userprofile/userprofileupdate/UserProfileContentContainer.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {imageURL} from "@/js/constants/serverUrl";
import {useUserStore} from "@/js/stores/useUserStore";
import {SchoolDataType} from "@/js/types/SchoolTypes";

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

//All sting defined constants
const leftHeadingPersonal = ref('Personal info')
const leftDescriptionPersonal = ref('Update your photo and personal details.')
const leftHeadingProfile = ref('Profile')
const leftDescriptionProfile = ref('Update your subjects and interests.')
const displayErrorMessageText = "Value is required"
const disabled = ref(true)
const booleanValueOnSubmitButton = ref(false)

const isLoading = ref(true)
const displayUserRole = computed(() => {
    switch (currentUser.value.role) {
    case "SCHLDR":
        return "School Principal"
    default:
        return "edSpark User"
    }
})

//vuelidation state and rules are defined
const statePersonal = reactive({
    displayName: currentUser.value.display_name,
    fullName: currentUser.value.full_name,
    userRole: displayUserRole.value,
    siteName: currentUser.value.site.site_name,
    emailId: currentUser.value.email,
    biography: ''
})
const rulesPersonal = {
    displayName: {required: required},
    fullName: {required: required},
    userRole: {required: required},
    siteName: {required: required},
    emailId: {required: required},
    biography: {required: required}
}
//vuelidation state and rules are defined
const stateProfile = reactive({
    subjectSelect: [],
    interestSelect: [],
    yearLevelSelect: []
})
const rulesProfile = {
    subjectSelect: {required: required},
    interestSelect: {required: required},
    yearLevelSelect: {required: required}
}
const vPersonal$ = useVuelidate(rulesPersonal, statePersonal)
const vProfile$ = useVuelidate(rulesProfile, stateProfile)
const handleReceiveYearListFromSelector = (yearList) => {
    stateProfile.yearLevelSelect = yearList
}
const handleReceiveSubjectsFromSelector = (subjectList) => {
    stateProfile.subjectSelect = subjectList
}
const handleReceiveInterestsFromSelector = (interestList) => {
    stateProfile.interestSelect = interestList
}

const uploadImageInstance = ref(false)
//handle submit data on click, posts the data to api and then store to the database.
const handleClickSubmitPersonalData = async () => {
    const result = await vPersonal$.value.$validate();
    if (result && fileDropped.value === true) {
        const data = {
            biography: statePersonal.biography
            // Add other properties as needed
        };
        axios.post(API_ENDPOINTS.USER.UPDATE_OR_CREATE_METADATA + currentUser.value.id, data)
            .then(res => {
                console.log(res.data);
                uploadImageInstance.value = true
                toast.success("Successfully submitted the Personal Info")
            })
            .catch(err => {
                console.log(err.value)
            })
    } else {
    }
}



const fetchUserMetadata = async () => {
    try{
        const response = await axios.get(API_ENDPOINTS.USER.GET_USER_PROFILE_METADATA + currentUser.value.id)

        const metadata = response.data.data;
        statePersonal.biography = metadata.find(item => item.user_meta_key === 'biography')
        const biography = statePersonal.biography ? statePersonal.biography.user_meta_value.replace(/^"(.*)"$/, '$1') : '';
        statePersonal.biography = biography || '';

        // converting string to an array.
        const yearLevelMetadataString =  metadata.find(item => item.user_meta_key === 'yearLevels')?.user_meta_value || [];
        const subjectMetadataString =  metadata.find(item => item.user_meta_key === 'subjects')?.user_meta_value || [];
        const interestMetadataString =  metadata.find(item => item.user_meta_key === 'interest')?.user_meta_value || [];

        const yearLevelMetadataArray = JSON.parse(yearLevelMetadataString)
        const subjectMetadataArray = JSON.parse(subjectMetadataString)
        const interestMetadataArray = JSON.parse(interestMetadataString)

        stateProfile.yearLevelSelect = yearLevelMetadataArray
        stateProfile.subjectSelect = subjectMetadataArray
        stateProfile.interestSelect = interestMetadataArray
        isLoading.value = false
    }
    catch (error) {
        console.error("Error fetching user metadata:", error)
    }
}

onMounted(fetchUserMetadata);

//handle submit data on click, posts the data to api and then store to the database.
const handleClickSubmitProfileData = async () => {
    const result = await vProfile$.value.$validate()
    if (result) {
        const data = {
            yearLevels: stateProfile.yearLevelSelect,
            subjects: stateProfile.subjectSelect,
            interest: stateProfile.interestSelect
        }
        axios.post(API_ENDPOINTS.USER.UPDATE_OR_CREATE_METADATA + currentUser.value.id, data)
            .then(res => {
                toast.success("Successfully submitted the Profile Info")
            })
            .catch(err => {
                console.log(err.value)
            })
        booleanValueOnSubmitButton.value = false

    }
    else {
    }
    booleanValueOnSubmitButton.value = true
}

const fileDropped = ref(false)
const handleReceiveFileDroppedInstance = (fileDroppedInsance) => {
    fileDropped.value = fileDroppedInsance
}

const handlePersonalCancelButton = () => {
    vPersonal$.value.$reset()
    statePersonal.biography = ('')
    statePersonal.displayName = ('')
    statePersonal.userRole = ('')
    statePersonal.fullName = ('')
    statePersonal.siteName = ('')
    statePersonal.emailId = ('')
    divContent.value = "Reset Content"
    reloadKey.value++
}

const divContent = ref('Initial content')
const reloadKey = ref(0)
const handleProfileCancelButton = () => {
    stateProfile.interestSelect = []
    stateProfile.yearLevelSelect = []
    stateProfile.subjectSelect = []
    divContent.value = "Reset Content"
    reloadKey.value++
}

</script>

<template>
    <div
        class="UserProfileSelectionMenuContainer bg-white flex flex-col gap-12 h-full w-full"
    >
        <Loader
            v-if="isLoading"
            class="mt-[15%]"
        >
            <div />
        </loader>
        <div v-if="!isLoading">
            <UserProfileContentContainer
                id="reloadableDiv"
                :key="reloadKey"
                :left-heading="leftHeadingPersonal"
                :left-description="leftDescriptionPersonal"
            >
                <template #content>
                    <div class="ml-4 mt-4">
                        Display
                    </div>
                    <input
                        v-model="vPersonal$.displayName.$model"
                        class="border-1 border-gray-400 mt-4 rounded-2xl"
                    >
                    <span>

                        <ErrorMessages
                            :v$="vPersonal$.displayName"
                            class="mb-6 mt-2"
                        />
                    </span>
                    <!--                    For the Full User name-->
                    <div class="ml-4 mt-4">
                        Full name
                    </div>
                    <input
                        v-model="vPersonal$.fullName.$model"
                        :disabled="disabled"
                        class="border-1 border-gray-400 mt-4 rounded-2xl"
                    >
                    <span>

                        <ErrorMessages
                            :v$="vPersonal$.fullName"
                            class="mb-6 mt-2"
                        />
                    </span>

                    <!--                    For the user designation-->
                    <div class="ml-4 mt-4">
                        Designation
                    </div>
                    <input
                        v-model="vPersonal$.userRole.$model"
                        :disabled="disabled"
                        class="border-1 border-gray-400 mt-4 rounded-2xl"
                    >
                    <span>

                        <ErrorMessages
                            :v$="vPersonal$.userRole"
                            class="mb-6 mt-2"
                        />
                    </span>

                    <!--                    For the School name-->
                    <div class="ml-4 mt-4">
                        School name
                    </div>
                    <input
                        v-model="vPersonal$.siteName.$model"
                        :disabled="disabled"
                        class="border-1 border-gray-400 mt-4 rounded-2xl"
                    >
                    <span>

                        <ErrorMessages
                            :v$="vPersonal$.siteName"
                            class="mb-6 mt-2"
                        />
                    </span>

                    <div class="ml-4 mt-4">
                        Email
                    </div>
                    <input
                        v-model="vPersonal$.emailId.$model"
                        :disabled="disabled"
                        class="border-1 border-gray-400 mt-4 rounded-2xl"
                    >
                    <span>

                        <ErrorMessages
                            :v$="vPersonal$.emailId"
                            class="mb-6 mt-2"
                        />
                    </span>

                    <div class="ml-4 mt-4">
                        Biography
                    </div>
                    <textarea
                        v-model="vPersonal$.biography.$model"
                        class="border-1 border-gray-400 h-72 mt-4 rounded-2xl"
                    />
                    <span>
                        <ErrorMessages
                            :v$="vPersonal$.biography"
                            class="mb-6 mt-2"
                        />
                    </span>

                    <UserAvatarChange
                        class="mt-6"
                        :send-image-upload-instance="uploadImageInstance"
                        @send-handle-file-dropped-instance="handleReceiveFileDroppedInstance"
                        @reset-image-upload-boolean="uploadImageInstance = false"
                    />
                </template>
                <template #buttons>
                    <GenericButton
                        id="cancelBtn"
                        class="
                            !bg-white
                            !h-14
                            !rounded-xl
                            !text-gray-700
                            !text-xl
                            border-[1px]
                            border-gray-400
                            ml-auto
                            p-4
                            w-28
                            "
                        :callback="handlePersonalCancelButton"
                    >
                        <template #default>
                            Cancel
                        </template>
                    </GenericButton>
                    <GenericButton
                        id="submitBtn"
                        class="!h-14 !rounded-xl p-4 text-xl hover:!bg-main-teal sm:!w-40"
                        :callback="handleClickSubmitPersonalData"
                    >
                        <template #default>
                            Save changes
                        </template>
                    </GenericButton>
                </template>
            </UserProfileContentContainer>
            <div class="border-[1px] border-gray-100" />
            <UserProfileContentContainer
                id="reloadableDiv"
                :key="reloadKey"
                :left-heading="leftHeadingProfile"
                :left-description="leftDescriptionProfile"
            >
                <template #content>
                    <div class="ml-4 mt-4">
                        <div class="ml-1">
                            Year levels
                        </div>
                        <div>
                            <UserChecklistSelector
                                :available-items="AvailableSchoolYearList"
                                :selected-items="stateProfile.yearLevelSelect"
                                @send-selected-values="handleReceiveYearListFromSelector"
                            />
                            <span v-if="((stateProfile.yearLevelSelect.length === 0) && (booleanValueOnSubmitButton===true))">
                                <CustomErrorMessages
                                    :error-text="displayErrorMessageText"
                                    class="mb-6"
                                />
                            </span>
                        </div>
                    </div>

                    <div class="ml-4 mt-4">
                        <div class="ml-1">
                            Subjects
                        </div>
                        <div class="mt-4 userSubjectSelectorContainer">
                            <!-- For Subjects-->
                            <UserCardItemSelector
                                v-model="vProfile$.subjectSelect.$model"
                                :available-items="AvailableSubjectsList"
                                :selected-items="stateProfile.subjectSelect"
                                @send-selected-values="handleReceiveSubjectsFromSelector"
                            />
                            <span v-if="((stateProfile.subjectSelect.length === 0) && (booleanValueOnSubmitButton===true))">
                                <CustomErrorMessages
                                    :error-text="displayErrorMessageText"
                                    class="mb-10 mt-6"
                                />
                            </span>
                        </div>
                    </div>

                    <div class="ml-4 mt-4">
                        <div class="ml-1">
                            Interests
                        </div>
                        <div class="mt-4 userInterestSelectorContainer">
                            <!-- For Interest-->
                            <UserCardItemSelector
                                v-model="vProfile$.interestSelect.$model"
                                :available-items="AvailableInterestsList"
                                :selected-items="stateProfile.interestSelect"
                                @send-selected-values="handleReceiveInterestsFromSelector"
                            />
                            <span v-if="((stateProfile.interestSelect.length === 0) && (booleanValueOnSubmitButton===true))">
                                <CustomErrorMessages
                                    :error-text="displayErrorMessageText"
                                    class="mb-10 mt-6"
                                />
                            </span>
                        </div>
                    </div>
                </template>
                <template #buttons>
                    <GenericButton
                        id="cancelBtn"
                        class="
                            !bg-white
                            !h-14
                            !rounded-xl
                            !text-gray-700
                            !text-xl
                            border-[1px]
                            border-gray-400
                            ml-auto
                            p-4
                            w-28
                            "
                        :callback="handleProfileCancelButton"
                    >
                        <template #default>
                            Cancel
                        </template>
                    </GenericButton>
                    <GenericButton
                        id="submitBtn"
                        class="!h-14 !rounded-xl p-4 text-xl hover:!bg-main-teal sm:!w-40"
                        :callback="handleClickSubmitProfileData"
                    >
                        <template #default>
                            Save changes
                        </template>
                    </GenericButton>
                </template>
            </UserProfileContentContainer>
        </div>
    </div>
</template>

<style scoped>

</style>
