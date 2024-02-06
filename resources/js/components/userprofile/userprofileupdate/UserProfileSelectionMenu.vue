<script setup lang="ts">

import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import axios from "axios";
import {storeToRefs} from "pinia";
import {onMounted, onUnmounted, reactive, ref, watchEffect} from "vue";

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import CustomErrorMessages from "@/js/components/feedbackform/CustomErrorMessages.vue";
import UserCardItemSelector from "@/js/components/userprofile/userprofileupdate/UserCardItemSelector.vue";
import UserChecklistSelector from "@/js/components/userprofile/userprofileupdate/UserChecklistSelector.vue";
import {
    AvailableInterestsList,
    AvailableSchoolYearList,
    AvailableSubjectsList
} from "@/js/components/userprofile/userprofileupdate/userListing";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const displayErrorMessageText = "Value is required"
const booleanValueOnSubmitButton = ref(false)
//states stored in variables
const userSelectedSubjects = ref([])
const userSelectedInterests = ref([])
const userSelectedYearLevel = ref([])

//for form validation
const state = reactive({
    displayName: currentUser.value.display_name,
    subjectSelect: userSelectedSubjects.value,
    interestSelect: userSelectedInterests.value,
    yearLevelSelect: userSelectedYearLevel.value,
    biographyInput: ''
})
const rules = {
    displayName: {required: required},
    subjectSelect: {required: required},
    interestSelect: {required: required},
    yearLevelSelect: {required: required},
    biographyInput: {required: required},
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

// Autosave feature building
const AUTOSAVE_INTERVAL = 5000; // Set the autosave interval msec

// Automatically save data to the database
const saveDataToDatabase = async () => {
    const result = await v$.value.$validate()
    if (result) {
        if ((userSelectedSubjects.value.length !== 0) && (userSelectedInterests.value.length !== 0) && (userSelectedYearLevel.value.length !== 0)) {
            const data = {
                subjects: userSelectedSubjects.value,
                interest: userSelectedInterests.value,
                yearLevels: userSelectedYearLevel.value,
                biography: v$.value.biographyInput.$model
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

// Set up an interval for autosave
const autosaveInterval = setInterval(async () => {
    await saveDataToDatabase();
}, AUTOSAVE_INTERVAL);

// Clear the autosave interval when the component is unmounted
onUnmounted(() => {
    clearInterval(autosaveInterval);
});




//used to get data, automatically from the database using api
onMounted(() =>{
    // populate userSelectedSubjects from the database
    //fetch here



})
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
        class="UserProfileSelectionMenuContainer bg-white flex flex-col gap-12 h-full rounded-2xl w-full"
    >
        <div class="m-10 text-2xl">
            <div>Display Name</div>
            <input
                v-model="v$.displayName.$model"
                class="border-1 mt-4 rounded-2xl"
            >
            <span>
                <ErrorMessages :v$="v$.displayName" />
            </span>
        </div>
        <div class="m-10">
            <div class="text-2xl">
                Year Level
            </div>
            <div
                class="grid grid-cols-6 mt-6"
            >
                <UserChecklistSelector
                    v-model="v$.yearLevelSelect.$model"
                    :available-items="AvailableSchoolYearList"
                    :selected-items="userSelectedYearLevel"
                    @send-selected-values="handleReceiveYearListFromSelector"
                />
            </div>
            <span v-if="((userSelectedYearLevel.length === 0) && (booleanValueOnSubmitButton===true))">
                <CustomErrorMessages
                    :error-text="displayErrorMessageText"
                    class="mt-6"
                /></span>
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
        <div class="m-10 text-2xl">
            <div>Biography</div>
            <input
                v-model="v$.biographyInput.$model"
                class="h-44 mt-4 rounded-2xl"
            >
            <span>
                <ErrorMessages :v$="v$.biographyInput" />
            </span>
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
