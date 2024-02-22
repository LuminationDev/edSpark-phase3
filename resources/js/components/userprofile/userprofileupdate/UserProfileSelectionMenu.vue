<script setup lang="ts">

import {left} from "@popperjs/core";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import axios from "axios";
import {storeToRefs} from "pinia";
import {computed, onMounted, onUnmounted, reactive, ref, watchEffect} from "vue";

import ProfilePlaceholder from "@/assets/images/profilePlaceholder.webp";
import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import CustomErrorMessages from "@/js/components/feedbackform/CustomErrorMessages.vue";
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

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const displayErrorMessageText = "Value is required"
const booleanValueOnSubmitButton = ref(false)
const showErrorOnSubmitButton = ref(false)
//states stored in variables
const userSelectedSubjects = ref([])
const userSelectedInterests = ref([])
const userSelectedYearLevel = ref([])
const biographyInput = ref('')
//variables for UserProfileContentContainer
const leftHeadingPersonalInfo = ref('Perosnal info')
const leftDescriptionPersonalInfo = ref('Update your photo and personal details.')
const leftHeadingProfile = ref('Profile')
const leftDescriptionProfile = ref('Update your subjects and interests.')
const isEditAvatar = ref(false);
//for form validation
const state = reactive({
    displayName: currentUser.value.display_name,
    subjectSelect: userSelectedSubjects.value,
    interestSelect: userSelectedInterests.value,
    yearLevelSelect: userSelectedYearLevel.value
})
const rules = {
    displayName: {required: required},
    subjectSelect: {required: required},
    interestSelect: {required: required},
    yearLevelSelect: {required: required}
}
const v$ = useVuelidate(rules, state)

//handling the received subjects and Year list data (in arrays) from their appropriate Selector in the form of emits.
const handleReceiveSubjectsFromSelector = (subjectList) => {
    userSelectedSubjects.value = subjectList
}
const handleReceiveInterestsFromSelector = (interestList) => {
    userSelectedInterests.value = interestList
}
const handleReceiveYearListFromSelector = (yearList) => {
    userSelectedYearLevel.value = yearList
}

//handle submit data on click, posts the data to api and then store to the database.
// const handleClickSubmitData = async () => {
//     const result = await v$.value.$validate()
//     if (result) {
//         if ((userSelectedSubjects.value.length !== 0) && (userSelectedInterests.value.length !== 0) && (userSelectedYearLevel.value.length !== 0)) {
//             const data = {
//                 subjects: userSelectedSubjects.value,
//                 interest: userSelectedInterests.value,
//                 yearLevels: userSelectedYearLevel.value,
//                 biography: v$.value.biographyInput.$model
//             }
//             axios.post(API_ENDPOINTS.USER.UPDATE_OR_CREATE_METADATA + currentUser.value.id, data)
//                 .then(res => {
//                     console.log(res.data)
//                 })
//                 .catch(err => {
//                     console.log(err.value)
//                 })
//             console.log("Data saved successfully")
//             booleanValueOnSubmitButton.value = false
//         }
//     }
//     else {
//         console.log("Data not saved successfully, Please enter all the values")
//     }
//     booleanValueOnSubmitButton.value = true
// }


// Save data on submit button
const saveDataToDatabase = async () => {
    showErrorOnSubmitButton.value = true
    const result = await v$.value.$validate()
    if (result) {
        if ((userSelectedSubjects.value.length !== 0) && (userSelectedInterests.value.length !== 0) && (userSelectedYearLevel.value.length !== 0)) {
            const data = {
                subjects: userSelectedSubjects.value,
                interest: userSelectedInterests.value,
                yearLevels: userSelectedYearLevel.value,
                biography: biographyInput.value
            }
            axios.post(API_ENDPOINTS.USER.UPDATE_OR_CREATE_METADATA + currentUser.value.id, data)
                .then(res => {
                    console.log(res.data)
                })
                .catch(err => {
                    console.log(err.value)
                })
            console.log("Data saved successfully")
            booleanValueOnSubmitButton.value = false
        }
    }
    else {
        console.log("Data not saved successfully, Please enter all the values")

    }
    booleanValueOnSubmitButton.value = true
    console.log("Autosave working");
};



onUnmounted(() => {

});




//used to get data, automatically from the database using api
onMounted(() =>{
    // populate userSelectedSubjects from the database
    //fetch here
})
//function for sending the data in array to UserProfileContentContainer
const itemsDataPersonal = ref([
    { rightHeading: 'Display name', rightContent: state.displayName },
    { rightHeading: 'Biography', rightContent: biographyInput }
]);
const itemsDataProfile = ref([
    { rightHeading: 'Year Levels', selectorChecklist: 'dssdfsdf',  hideUserCheckListSelector: true  },
    { rightHeading: 'Subjects', selectorChecklist: 'hh', hideUserCardListSelector: true, showError: true },
    { rightHeading: 'Interests', selectorChecklist: 'hh', hideUserCardListSelector: true, showError: true },
]);
const avatarUrl = ref('')
const userAvatarUrlWithFallback = computed(() => {
    if (avatarUrl.value) {
        return `${imageURL}/${avatarUrl.value}`
    } else {
        return ProfilePlaceholder
    }
})
const filteredItems = computed(() => itemsDataProfile.value.filter(item => item.hideUserCheckListSelector || item.hideUserCardListSelector));



</script>

<template>
    <!--    <div class="bg-red-100 border-2 cursor-pointer fixed bottom-[5rem] left-[6rem] flex flex-row rounded-2xl w-24 z-[70]">-->
    <!--        <div-->
    <!--            class="align-center ml-2 text-2xl"-->
    <!--            @click="handleClickSubmitData"-->
    <!--        >-->
    <!--            Submit Button-->
    <!--        </div>-->
    <!--    </div>-->
    <div
        class="UserProfileSelectionMenuContainer bg-white flex flex-col gap-12 h-full w-full"
    >
        <div>
            <UserProfileContentContainer
                :left-heading="leftHeadingPersonalInfo"
                :left-description="leftDescriptionPersonalInfo"
                :items="itemsDataPersonal"
                :right-content-index="1"
                :is-edit-avatar="isEditAvatar"
                :user-avatar-url-with-fallback="userAvatarUrlWithFallback"
                :send-save-data-to-child="saveDataToDatabase"
                :hide-user-check-list-selector="false"
            />
        </div>
        <div>
            <UserProfileContentContainer
                :hide-input-field="false"
                :hide-profile-picture="false"
                :left-heading="leftHeadingProfile"
                :left-description="leftDescriptionProfile"
                :items="filteredItems"
                :right-content-check-list-selector="1"
                :is-edit-avatar="isEditAvatar"
                :user-avatar-url-with-fallback="userAvatarUrlWithFallback"
                :selector-checklist="userSelectedYearLevel"
                :selector-cardlist="userSelectedSubjects"
                :available-years-list-items="AvailableSchoolYearList"
                :available-subjects-list-items="AvailableSubjectsList"
                :send-save-data-to-child="saveDataToDatabase"
                :send-boolean-value-to-chid="booleanValueOnSubmitButton"
                :send-selected-check-values="handleReceiveYearListFromSelector"
                :send-selected-card-values="handleReceiveSubjectsFromSelector"
            />
        </div>
        <div
            class="m-10 text-2xl"
        >
            <div>Subjects</div>
            <div
                class="mt-6 userSubjectSelectorContainer"
            >
                <UserCardItemSelector
                    v-model="v$.subjectSelect.$model"
                    :available-items="AvailableSubjectsList"
                    :selected-items="userSelectedSubjects"
                    @send-selected-values="handleReceiveSubjectsFromSelector"
                />
            </div>
            <span v-if="((userSelectedSubjects.length === 0) && (booleanValueOnSubmitButton===true))">
                <CustomErrorMessages
                    :error-text="displayErrorMessageText"
                    class="mt-6"
                /></span>
        </div>
        <div class="m-10 text-2xl">
            <div>Interests</div>
            <div class="mt-6 userInterestSelectorContainer">
                <UserCardItemSelector
                    v-model="v$.interestSelect.$model"
                    :available-items="AvailableInterestsList"
                    :selected-items="userSelectedInterests"
                    @send-selected-values="handleReceiveInterestsFromSelector"
                />
            </div>
            <span v-if="((userSelectedInterests.length === 0) && (booleanValueOnSubmitButton===true))">
                <CustomErrorMessages
                    :error-text="displayErrorMessageText"
                    class="mt-6"
                /></span>
        </div>
        <!--        <div class="m-10 text-2xl">-->
        <!--            <div>Biography</div>-->
        <!--            <input-->
        <!--                v-model="v$.biographyInput.$model"-->
        <!--                class="h-44 mt-4 rounded-2xl"-->
        <!--            >-->
        <!--            <span>-->
        <!--                <ErrorMessages :v$="v$.biographyInput" />-->
        <!--            </span>-->
        <!--        </div>-->
        <div>
            <GenericButton
                :callback="saveDataToDatabase"
                class="!h-10 m-10 ml-auto w-32"
            >
                Submit
            </GenericButton>
        </div>
    </div>
</template>

<style scoped>
.checkbox {
    display: none;
}
.label {
    border: 1px solid #0d9aa6;
    display: inline-block;
    background: white;
    opacity: 1;
    /* background: url("unchecked.png") no-repeat left center; */
    /* padding-left: 15px; */
}
input[type=checkbox]:checked + .label {
    background: #55c5c9;

    opacity: 1;
    /* background-image: url("checked.png"); */
}
</style>
