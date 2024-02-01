<script setup lang="ts" xmlns:src="http://www.w3.org/1999/xhtml">

import {onMounted, ref} from "vue";

import {AvailableSubjectsList} from "@/js/components/userprofile/userprofileupdate/userListing";
import UserSelector from "@/js/components/userprofile/userprofileupdate/UserSelector.vue";
const props = defineProps({
    displayUsernameInput:{
        type: String,
        required: true
    }
})

const userSelectedSubjects = ref(['English', 'HASS'])

const handleSubmitUserSubjects = () => {
    const requestData = {
        subjects: userSelectedSubjects
    }
}

const handleReceiveSubjectsFromSelector = (subjectList) => {
    userSelectedSubjects.value = subjectList
};

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
            </div>
        </div>
        <div class="m-10 text-2xl">
            <div>Interests</div>
            <div class="flex gap-10">
                <div class="flex gap-2 mt-2">
                    <input
                        type="checkbox"
                        class="h-20 mt-2 rounded-lg w-20"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <input
                        type="checkbox"
                        class="h-20 mt-2 rounded-lg w-20"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <input
                        type="checkbox"
                        class="h-20 mt-2 rounded-lg w-20"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <input
                        type="checkbox"
                        class="h-20 mt-2 rounded-lg w-20"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <input
                        type="checkbox"
                        class="h-20 mt-2 rounded-lg w-20"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <input
                        type="checkbox"
                        class="h-20 mt-2 rounded-lg w-20"
                    >
                </div>
                <div class="flex gap-2 mt-2">
                    <input
                        type="checkbox"
                        class="h-20 mt-2 rounded-lg w-20"
                    >
                </div>
            </div>
        </div>


        <div class="m-10 text-2xl">
            <div>Subjects</div>
            <div class="flex flex-row userSubjectSelectorContainer">
                <!-- For Subjects-->
                <UserSelector
                    :available-items="AvailableSubjectsList"
                    :selected-items="userSelectedSubjects"
                    @send-selected-values="handleReceiveSubjectsFromSelector"
                />
            </div>
        </div>





























        <div class="m-10 text-2xl">
            <div>Extra Items</div>
            <div class="flex gap-10">
                <div class="flex gap-2 mt-2">
                    <label><input
                        type="checkbox"
                        class="checkbox"
                    ><span class="h-20 label mt-2 rounded-lg w-20"><img
                        src="https://cdn-icons-png.flaticon.com/512/2815/2815428.png"
                        class="align-center bg-transparent h-12 m-auto mt-4 w-12"
                    ></span></label>
                </div>
                <div class="flex gap-2 mt-2">
                    <label><input
                        type="checkbox"
                        class="checkbox"
                    ><span class="h-20 label mt-2 rounded-lg w-20"><img
                        src=""
                        class="align-center bg-transparent h-12 m-auto mt-4 w-12"
                    ></span></label>
                </div>
                <div class="flex gap-2 mt-2">
                    <label><input
                        type="checkbox"
                        class="checkbox"
                    ><span class="h-20 label mt-2 rounded-lg w-20"><img
                        src=""
                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"
                    ></span></label>
                </div>
                <div class="flex gap-2 mt-2">
                    <label><input
                        type="checkbox"
                        class="checkbox"
                    ><span class="h-20 label mt-2 rounded-lg w-20"><img
                        src=""
                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"
                    ></span></label>
                </div>
                <div class="flex gap-2 mt-2">
                    <label><input
                        type="checkbox"
                        class="checkbox"
                    ><span class="h-20 label mt-2 rounded-lg w-20"><img
                        src=""
                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"
                    ></span></label>
                </div>
                <div class="flex gap-2 mt-2">
                    <label><input
                        type="checkbox"
                        class="checkbox"
                    ><span class="h-20 label mt-2 rounded-lg w-20"><img
                        src=""
                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"
                    ></span></label>
                </div>
                <div class="flex gap-2 mt-2">
                    <label><input
                        type="checkbox"
                        class="checkbox"
                    ><span class="h-20 label mt-2 rounded-lg w-20"><img
                        src=""
                        class="align-center bg-transparent h-12 m-auto mt-2 w-12"
                    ></span></label>
                </div>
            </div>
        </div>

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
