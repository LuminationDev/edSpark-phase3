<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import axios from "axios";
import {storeToRefs} from "pinia";
import {emits} from "v-calendar/dist/types/src/use/datePicker";
import {computed, reactive, Ref, ref} from "vue";

import ProfilePlaceholder from "@/assets/images/profilePlaceholder.webp";
import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CustomErrorMessages from "@/js/components/feedbackform/CustomErrorMessages.vue";
import SchoolImageChange from "@/js/components/schoolsingle/schoolContent/SchoolImageChange.vue";
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

const booleanValueOnSubmitButton = ref(false)
//vuelidation state and rules are defined
const statePersonal = reactive({
    displayName: currentUser.value.display_name,
    biography: ''
})
const rulesPersonal = {
    displayName: {required: required},
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
    uploadImageInstance.value = true
    const result = await vPersonal$.value.$validate();
    if (result && fileDropped.value === true) {
        const data = {
            biography: statePersonal.biography
            // Add other properties as needed
        };
        axios.post(API_ENDPOINTS.USER.UPDATE_OR_CREATE_METADATA + currentUser.value.id, data)
            .then(res => {
                console.log(res.data);
                console.log("Personal Data saved successfully");
                uploadImageInstance.value = true
            })
            .catch(err => {
                console.error(err);
                console.log("Error occurred while saving personal data");
            });
    } else {
        console.log("Personal Data not saved successfully. Please enter all the values.");
    }
};

//handle submit data on click, posts the data to api and then store to the database.
const handleClickSubmitProfileData = async () => {
    const result = await vProfile$.value.$validate()
    if (result) {
        if ((stateProfile.subjectSelect.length !== 0) && (stateProfile.yearLevelSelect.length !== 0)) {
            const data = {
                yearLevels: stateProfile.yearLevelSelect,
                subjects: stateProfile.subjectSelect,
                interest: stateProfile.interestSelect
            }
            axios.post(API_ENDPOINTS.USER.UPDATE_OR_CREATE_METADATA + currentUser.value.id, data)
                .then(res => {
                    console.log(res.data)
                })
                .catch(err => {
                    console.log(err.value)
                })
            console.log("Profile Data saved successfully")
            booleanValueOnSubmitButton.value = false
        }
    }
    else {
        console.log("Profile Data not saved successfully, Please enter all the values")
    }
    booleanValueOnSubmitButton.value = true
}

const logoStorage = ref(null)
const handleReceivePhotoFromContent = (type, file) => {
    switch (type) {
    case 'logo':
        console.log('received logo')
        logoStorage.value = file
        break;
    default:
        console.log('received unknown type image')
        break;
    }
}

const fileDropped = ref(false)
const handleReceiveFileDroppedInstance = (fileDroppedInsance) => {
    fileDropped.value = fileDroppedInsance

}


</script>

<template>
    <div
        class="UserProfileSelectionMenuContainer bg-white flex flex-col gap-12 h-full w-full"
    >
        <div>
            <UserProfileContentContainer
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

                        <ErrorMessages :v$="vPersonal$.displayName" />
                    </span>

                    <div class="ml-4 mt-4">
                        Biography
                    </div>
                    <input
                        v-model="vPersonal$.biography.$model"
                        class="border-1 border-gray-400 h-64 mt-4 rounded-2xl"
                    >
                    <span>
                        <ErrorMessages :v$="vPersonal$.biography" />
                    </span>

                    <UserAvatarChange
                        class="mt-6"
                        :send-image-upload-instance="uploadImageInstance"
                        @send-uploaded-photo-to-content="handleReceivePhotoFromContent"
                        @send-handle-file-dropped-instance="handleReceiveFileDroppedInstance"
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
                        callback="d"
                    >
                        <template #default>
                            Cancel
                        </template>
                    </GenericButton>
                    <GenericButton
                        id="submitBtn"
                        class="!h-14 !rounded-xl !text-xl p-4 w-28 hover:!bg-main-teal lg:!w-40"
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
                :left-heading="leftHeadingProfile"
                :left-description="leftDescriptionProfile"
            >
                <template #content>
                    <div class="ml-4 mt-4">
                        <div class="ml-1">
                            Year levels
                        </div>
                        <div class="grid grid-cols-6 mt-6 userYearLevelSelectorContainer">
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
                                    class="mb-6 mt-6"
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
                                    class="mb-6 mt-6"
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
                        callback="d"
                    >
                        <template #default>
                            Cancel
                        </template>
                    </GenericButton>
                    <GenericButton
                        id="submitBtn"
                        class="!h-14 !rounded-xl !text-xl p-4 w-28 hover:!bg-main-teal lg:!w-40"
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
