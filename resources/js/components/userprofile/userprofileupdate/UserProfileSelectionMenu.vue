<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import axios from "axios";
import {storeToRefs} from "pinia";
import {computed, onMounted, reactive, Ref, ref} from "vue";
import {Tippy} from "vue-tippy";
import {toast} from "vue3-toastify";

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CustomErrorMessages from "@/js/components/feedbackform/CustomErrorMessages.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import UserBookmark from "@/js/components/userprofile/UserBookmark.vue";
import UserAvatarChange from "@/js/components/userprofile/userprofileupdate/UserAvatarChange.vue";
import UserCardItemSelector from "@/js/components/userprofile/userprofileupdate/UserCardItemSelector.vue";
import UserChecklistSelector from "@/js/components/userprofile/userprofileupdate/UserChecklistSelector.vue";
import {
    AvailableInterestsList,
    AvailableSchoolYearList,
    AvailableSubjectsList
} from "@/js/components/userprofile/userprofileupdate/userListing";
import UserUnreadNotificationLayout
    from "@/js/components/userprofile/userprofileupdate/usernotification/UserUnreadNotificationLayout.vue";
import UserProfileContentContainer from "@/js/components/userprofile/userprofileupdate/UserProfileContentContainer.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

//All sting defined constants
const leftHeadingPersonal = ref('Personal info')
const leftDescriptionPersonal = ref('Update your photo and personal details.')
const leftHeadingBookmark = ref('Bookmarks')
const leftDescriptionBookmark = ref('Your favourite bookmarks are here')
const leftHeadingProfile = ref('Profile')
const leftDescriptionProfile = ref('Update your subjects and interests.')
const leftHeadingNotification = ref('Notifications')
const leftDescriptionNotification = ref('Stay up to date with the latest activities.')
const disabledTippyMessage = "This information is retrieved from EdPass and cannot be changed through edSpark. Please change on EdPass if necessary."
const displayErrorMessageText = "Value is required"
const disabled = ref(true)
const booleanValueOnSubmitButton = ref(false)

const isLoading = ref(true)
const displayUserRole = computed(() => {
    switch (currentUser.value.role) {
    case "Superadmin":
        return "Superadmin"
    case "SCHLDR":
    case "PRESCLDR":
        return "School Principal"
    case "SITELDR":
        return "Site Leader"
    case "PSACT":
        return "Public Sector Act"
    default:
        return "edSpark User"
    }
})

// to be used as a fallback content when cancel is pressed
const initialPersonalState = reactive({
    biography: ''
})

const initialProfileState = reactive({
    subjectSelect: [],
    interestSelect: [],
    yearLevelSelect: []
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
    fullName: {},
    userRole: {},
    siteName: {},
    emailId: {},
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

const fetchUserMetadata = async () => {
    try {
        const response = await axios.get(API_ENDPOINTS.USER.GET_USER_PROFILE_METADATA + currentUser.value.id)
        const metadata = response.data.data;
        statePersonal.biography = metadata.find(item => item.user_meta_key === 'biography')
        const biography = statePersonal.biography ? statePersonal.biography.user_meta_value.replace(/^"(.*)"$/, '$1') : '';
        statePersonal.biography = biography || '';
        initialPersonalState.biography = biography || '';

        // converting string to an array.
        const yearLevelMetadataString = metadata.find(item => item.user_meta_key === 'yearLevels')?.user_meta_value || [];
        const subjectMetadataString = metadata.find(item => item.user_meta_key === 'subjects')?.user_meta_value || [];
        const interestMetadataString = metadata.find(item => item.user_meta_key === 'interest')?.user_meta_value || [];

        const yearLevelMetadataArray = JSON.parse(yearLevelMetadataString)
        const subjectMetadataArray = JSON.parse(subjectMetadataString)
        const interestMetadataArray = JSON.parse(interestMetadataString)
        // these are modified by the UI as we use the form
        stateProfile.yearLevelSelect = yearLevelMetadataArray
        stateProfile.subjectSelect = subjectMetadataArray
        stateProfile.interestSelect = interestMetadataArray
        // fallback when cancel is pressed
        initialProfileState.yearLevelSelect = yearLevelMetadataArray
        initialProfileState.subjectSelect = subjectMetadataArray
        initialProfileState.interestSelect = interestMetadataArray

    } catch (error) {
        console.error("Error fetching user metadata:", error)
    } finally {
        isLoading.value = false
    }
}

onMounted(fetchUserMetadata);

//handle submit data on click, posts the data to api and then store to the database.
const handleClickSubmitPersonalData = async () => {
    const result = await vPersonal$.value.$validate();
    if (result) {
        const biographyData = {
            biography: statePersonal.biography
        };

        const displayNameData = {
            data: {
                updateField: "display_name",
                updateValue: statePersonal.displayName

            }
        }

        const biographyPromise = axios.post(API_ENDPOINTS.USER.UPDATE_OR_CREATE_METADATA + currentUser.value.id, biographyData)
        const displayNamePromise = axios.post(API_ENDPOINTS.USER.UPDATE_USER + currentUser.value.id, displayNameData)

        return Promise.all([biographyPromise, displayNamePromise]).then(() => {
            uploadImageInstance.value = true
            toast.success("Successfully submitted personal info")
        }).catch(err => {
            console.log('Error during submission of personal data')
            console.error(err.message)
        })

    }
}


//handle submit data on click, posts the data to api and then store to the database.
const handleClickSubmitProfileData = async () => {
    const result = await vProfile$.value.$validate()
    if (result) {
        const data = {
            yearLevels: stateProfile.yearLevelSelect,
            subjects: stateProfile.subjectSelect,
            interest: stateProfile.interestSelect
        }
        return axios.post(API_ENDPOINTS.USER.UPDATE_OR_CREATE_METADATA + currentUser.value.id, data)
            .then(() => {
                toast.success("Successfully submitted profile info")
                booleanValueOnSubmitButton.value = false
            })
            .catch(err => {
                console.log(err.value)
            })
    }
    booleanValueOnSubmitButton.value = true
}

const fileDropped = ref(false)
const reloadKey = ref(0)

const handleReceiveFileDroppedInstance = (fileDroppedInsance) => {
    fileDropped.value = fileDroppedInsance
}

const handlePersonalCancelButton = () => {
    vPersonal$.value.$reset()
    statePersonal.biography = initialPersonalState.biography
    statePersonal.displayName = currentUser.value.display_name
    statePersonal.fullName = currentUser.value.full_name
    statePersonal.userRole = displayUserRole.value
    statePersonal.siteName = currentUser.value.site.site_name
    statePersonal.emailId = currentUser.value.email
    reloadKey.value++
}

const handleProfileCancelButton = () => {
    stateProfile.interestSelect = initialProfileState.interestSelect
    stateProfile.yearLevelSelect = initialProfileState.yearLevelSelect
    stateProfile.subjectSelect = initialProfileState.subjectSelect
    reloadKey.value++
}

const userNotificationLargeLayout = computed(() =>
{
    if (currentUser.value.id) {
        console.log("View all button pressed")
        return `/notifications/${currentUser.value.id}`
    } else return ''
})


</script>

<template>
    <div
        class="UserProfileSelectionMenuContainer bg-white flex flex-col gap-12 h-full w-full"
    >
        <Loader
            v-if="isLoading"
            class="mt-[15%]"
        />
        <div v-if="!isLoading">
            <UserProfileContentContainer
                id="reloadableDiv"
                :key="reloadKey"
                :left-heading="leftHeadingPersonal"
                :left-description="leftDescriptionPersonal"
            >
                <template #content>
                    <!--                    For the Full User name-->
                    <div class="ml-4 mt-2">
                        Full name
                    </div>
                    <tippy
                        :content="disabledTippyMessage"
                    >
                        <input
                            v-model="vPersonal$.fullName.$model"
                            :disabled="disabled"
                            class="border-1 border-gray-300 mt-2 rounded-lg"
                        >
                    </tippy>
                    <span>

                        <ErrorMessages
                            :v$="vPersonal$.fullName"
                            class="mb-6 mt-2"
                        />
                    </span>


                    <div class="ml-4 mt-2">
                        User role
                    </div>
                    <tippy :content="disabledTippyMessage">
                        <input
                            v-model="vPersonal$.userRole.$model"
                            :disabled="disabled"
                            class="border-1 border-gray-300 mt-2 rounded-lg"
                        >
                    </tippy>
                    <span>

                        <ErrorMessages
                            :v$="vPersonal$.userRole"
                            class="mb-6 mt-2"
                        />
                    </span>
                    <!--                    For the School name-->
                    <div class="ml-4 mt-2">
                        School name
                    </div>
                    <tippy :content="disabledTippyMessage">
                        <input
                            v-model="vPersonal$.siteName.$model"
                            :disabled="disabled"
                            class="border-1 border-gray-300 mt-2 rounded-lg"
                        >
                    </tippy>
                    <span>
                        <ErrorMessages
                            :v$="vPersonal$.siteName"
                            class="mb-6 mt-2"
                        />
                    </span>

                    <div class="ml-4 mt-2">
                        Email
                    </div>
                    <tippy :content="disabledTippyMessage">
                        <input
                            v-model="vPersonal$.emailId.$model"
                            :disabled="disabled"
                            class="border-1 border-gray-300 mt-2 rounded-lg"
                        >
                    </tippy>
                    <span>

                        <ErrorMessages
                            :v$="vPersonal$.emailId"
                            class="mb-6 mt-2"
                        />
                    </span>
                    <div class="ml-4 mt-2">
                        Display Name
                    </div>
                    <input
                        v-model="vPersonal$.displayName.$model"
                        class="border-1 border-gray-300 drop-shadow-sm mt-2 rounded-lg"
                    >
                    <span>

                        <ErrorMessages
                            :v$="vPersonal$.displayName"
                            class="mb-6 mt-2"
                        />
                    </span>

                    <div class="ml-4 mt-2">
                        Biography
                    </div>
                    <textarea
                        v-model="vPersonal$.biography.$model"
                        class="border-1 border-gray-300 h-72 mt-2 rounded-lg"
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
                    <div class="border-[1px] border-gray-200 mt-10" />
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
                    <div class="ml-4 mt-2">
                        <div class="ml-1">
                            Year levels
                        </div>
                        <div>
                            <UserChecklistSelector
                                :available-items="AvailableSchoolYearList"
                                :selected-items="vProfile$.yearLevelSelect.$model"
                                @send-selected-values="handleReceiveYearListFromSelector"
                            />
                            <span
                                v-if="((stateProfile.yearLevelSelect.length === 0) && (booleanValueOnSubmitButton===true))"
                            >
                                <CustomErrorMessages
                                    :error-text="displayErrorMessageText"
                                    class="mb-6"
                                />
                            </span>
                        </div>
                    </div>

                    <div class="ml-4 mt-2">
                        <div class="ml-1">
                            Subjects
                        </div>
                        <div class="mt-2 userSubjectSelectorContainer">
                            <!-- For Subjects-->
                            <UserCardItemSelector
                                v-model="vProfile$.subjectSelect.$model"
                                :available-items="AvailableSubjectsList"
                                :selected-items="stateProfile.subjectSelect"
                                @send-selected-values="handleReceiveSubjectsFromSelector"
                            />
                            <span
                                v-if="((stateProfile.subjectSelect.length === 0) && (booleanValueOnSubmitButton===true))"
                            >
                                <CustomErrorMessages
                                    :error-text="displayErrorMessageText"
                                    class="mb-10 mt-6"
                                />
                            </span>
                        </div>
                    </div>

                    <div class="ml-4 mt-2">
                        <div class="ml-1">
                            Interests
                        </div>
                        <div class="mt-2 userInterestSelectorContainer">
                            <!-- For Interest-->
                            <UserCardItemSelector
                                v-model="vProfile$.interestSelect.$model"
                                :available-items="AvailableInterestsList"
                                :selected-items="stateProfile.interestSelect"
                                @send-selected-values="handleReceiveInterestsFromSelector"
                            />
                            <span
                                v-if="((stateProfile.interestSelect.length === 0) && (booleanValueOnSubmitButton===true))"
                            >
                                <CustomErrorMessages
                                    :error-text="displayErrorMessageText"
                                    class="mb-10 mt-6"
                                />
                            </span>
                        </div>
                    </div>
                    <div class="border-[1px] border-gray-200 mt-10" />
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
            <div class="border-[1px] border-gray-100" />
            <UserProfileContentContainer
                id="reloadableDiv"
                :key="reloadKey"
                :left-heading="leftHeadingNotification"
                :left-description="leftDescriptionNotification"
            >
                <template #content>
                    <div class="ml-4 mt-2">
                        <div class="flex flex-row">
                            <div class="ml-1">
                                Recent Activities
                            </div>
                            <div class="cursor-pointer ml-auto mr-4 underline">
                                <router-link
                                    :to="userNotificationLargeLayout"
                                >
                                    View all
                                </router-link>
                            </div>
                        </div>
                        <div>
                            <UserUnreadNotificationLayout />
                        </div>
                    </div>
                </template>
            </UserProfileContentContainer>
            <UserProfileContentContainer
                id="reloadableDiv"
                :key="reloadKey"
                :left-heading="leftHeadingBookmark"
                :left-description="leftDescriptionBookmark"
                class="UserBookmarkListContainer flex flex-col pt-12"
            >
                <template #content>
                    <div class="flex flex-row mb-6 ml-4 mt-2">
                        Your Bookmarks
                    </div>
                    <div class="ml-4">
                        <UserBookmark />
                    </div>
                </template>
            </UserProfileContentContainer>
        </div>
    </div>
</template>

<style scoped>
input:disabled {
    background-color: #F5F5F4;
}
</style>
