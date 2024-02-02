<script setup lang="ts">

import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import axios from "axios";
import {storeToRefs} from "pinia";
import {onMounted, reactive, ref} from "vue";

import UserCardItemSelector from "@/js/components/userprofile/userprofileupdate/UserCardItemSelector.vue";
import {AvailableInterestsList,AvailableSubjectsList} from "@/js/components/userprofile/userprofileupdate/userListing";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {useUserStore} from "@/js/stores/useUserStore";

const userSelectedSubjects = ref([])
const userSelectedInterests = ref([])
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

//for form validation
const state = reactive({
    displayName: currentUser.value.display_name,
    yearLevel: currentUser.value
})
const rules = {
    displayName: {required},
}
const v$ = useVuelidate(rules, state)

//handling the received subjects list data (in arrays) from UserCardItemSelector in the form of emits.
const handleReceiveSubjectsFromSelector = (subjectList) => {
    userSelectedSubjects.value = subjectList
};
//handling the received Interests list data (in arrays) from UserCardItemSelector in the form of emits.
const handleReceiveInterestsFromSelector = (interestList) => {
    userSelectedInterests.value = interestList
};

//handle submit data on click, posts the data to api and then store to the database.
const handleClickSubmitData = () => {
    const data = {
        subjects: userSelectedSubjects.value,
        interest: userSelectedInterests.value
    }
    axios.post(API_ENDPOINTS.USER.UPDATE_OR_CREATE_METADATA + currentUser.value.id, data)
        .then(res => {
            console.log(res.data)
        })
        .catch(err => {
            console.log(err.value)
        })
}

//used to get data, automatically from the database using api
onMounted(() =>{
    // populate userSelectedSubjects from the database
    //fetch here


})

</script>

<template>
    <div
        class="UserProfileSelectionMenuContainer bg-white flex flex-col gap-12 h-full rounded-2xl w-full"
    >
        <div class="m-10 text-2xl">
            <div>Display Name</div>
            <input
                v-model="v$.displayName.$model"
                class="border-1 mt-4 rounded-2xl"
            >
        </div>
        <div class="m-10 text-2xl">
            <div>Year Level</div>
            <div class="flex gap-10">
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y1
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y2
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y3
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y4
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y5
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y6
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y7
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y8
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y9
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y10
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y11
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <p class="text-lg">
                        Y12
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                    >
                </div>
                <div class="-ml-72 fixed flex gap-2 mt-2">
                    <p class="text-lg">
                        Submit Button
                    </p><input
                        type="checkbox"
                        class="h-6 mt-1 w-6"
                        @click="handleClickSubmitData"
                    >
                </div>
            </div>
        </div>
        <div class="m-10 text-2xl">
            <div>Subjects</div>
            <div class="grid grid-cols-5 gap-[2%] mt-6 userSubjectSelectorContainer">
                <!-- For Subjects-->
                <UserCardItemSelector
                    :available-items="AvailableSubjectsList"
                    :selected-items="userSelectedSubjects"
                    @send-selected-values="handleReceiveSubjectsFromSelector"
                />
            </div>
        </div>
        <div class="m-10 text-2xl">
            <div>Interests</div>
            <div class="grid grid-cols-3 gap-[3%] mt-6 userInterestSelectorContainer">
                <!-- For Interests-->
                <UserCardItemSelector
                    :available-items="AvailableInterestsList"
                    :selected-items="userSelectedInterests"
                    @send-selected-values="handleReceiveInterestsFromSelector"
                />
            </div>
        </div>

        <!--        <div class="m-10 text-2xl">-->
        <!--            <div>Interests</div>-->
        <!--            <div class="flex gap-10">-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <input-->
        <!--                        type="checkbox"-->
        <!--                        class="h-20 mt-2 rounded-lg w-20"-->
        <!--                    >-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <input-->
        <!--                        type="checkbox"-->
        <!--                        class="h-20 mt-2 rounded-lg w-20"-->
        <!--                    >-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <input-->
        <!--                        type="checkbox"-->
        <!--                        class="h-20 mt-2 rounded-lg w-20"-->
        <!--                    >-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <input-->
        <!--                        type="checkbox"-->
        <!--                        class="h-20 mt-2 rounded-lg w-20"-->
        <!--                    >-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <input-->
        <!--                        type="checkbox"-->
        <!--                        class="h-20 mt-2 rounded-lg w-20"-->
        <!--                    >-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <input-->
        <!--                        type="checkbox"-->
        <!--                        class="bg-[#5A67D8] h-20 mt-2 rounded-lg w-20"-->
        <!--                        @click="handleClickSubmitData"-->
        <!--                    >-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <input-->

        <!--                        type="checkbox"-->
        <!--                        class="bg-green-100 cursor-pointer h-20 mt-2 rounded-lg w-20"-->
        <!--                    >-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->


        <!--        <div class="m-10 text-2xl">-->
        <!--            <div>Extra Items</div>-->
        <!--            <div class="flex gap-10">-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <label><input-->
        <!--                        type="checkbox"-->
        <!--                        class="checkbox"-->
        <!--                    ><span class="h-20 label mt-2 rounded-lg w-20"><img-->
        <!--                        src="https://cdn-icons-png.flaticon.com/512/2815/2815428.png"-->
        <!--                        class="align-center bg-transparent h-12 m-auto mt-4 w-12"-->
        <!--                    ></span></label>-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <label><input-->
        <!--                        type="checkbox"-->
        <!--                        class="checkbox"-->
        <!--                    ><span class="h-20 label mt-2 rounded-lg w-20"><img-->
        <!--                        src=""-->
        <!--                        class="align-center bg-transparent h-12 m-auto mt-4 w-12"-->
        <!--                    ></span></label>-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <label><input-->
        <!--                        type="checkbox"-->
        <!--                        class="checkbox"-->
        <!--                    ><span class="h-20 label mt-2 rounded-lg w-20"><img-->
        <!--                        src=""-->
        <!--                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"-->
        <!--                    ></span></label>-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <label><input-->
        <!--                        type="checkbox"-->
        <!--                        class="checkbox"-->
        <!--                    ><span class="h-20 label mt-2 rounded-lg w-20"><img-->
        <!--                        src=""-->
        <!--                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"-->
        <!--                    ></span></label>-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <label><input-->
        <!--                        type="checkbox"-->
        <!--                        class="checkbox"-->
        <!--                    ><span class="h-20 label mt-2 rounded-lg w-20"><img-->
        <!--                        src=""-->
        <!--                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"-->
        <!--                    ></span></label>-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <label><input-->
        <!--                        type="checkbox"-->
        <!--                        class="checkbox"-->
        <!--                    ><span class="h-20 label mt-2 rounded-lg w-20"><img-->
        <!--                        src=""-->
        <!--                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"-->
        <!--                    ></span></label>-->
        <!--                </div>-->
        <!--                <div class="flex gap-2 mt-2">-->
        <!--                    <label><input-->
        <!--                        type="checkbox"-->
        <!--                        class="checkbox"-->
        <!--                    ><span class="h-20 label mt-2 rounded-lg w-20"><img-->
        <!--                        src=""-->
        <!--                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"-->
        <!--                    ></span></label>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

        <div class="m-10 text-2xl">
            <div>Biography</div>
            <input class="h-44 mt-4 rounded-2xl">
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
