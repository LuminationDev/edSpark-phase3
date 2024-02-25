<script setup lang="ts">

import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import axios from "axios";
import {defineEmits, onMounted, reactive, ref} from "vue";

import ErrorMessages from "@/js/components/bases/ErrorMessages.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import Edit from "@/js/components/svg/Edit.vue";
import ErrorIconSmall from "@/js/components/svg/ErrorIconSmall.vue";
import UserCardItemSelector from "@/js/components/userprofile/userprofileupdate/UserCardItemSelector.vue";
import UserChecklistSelector from "@/js/components/userprofile/userprofileupdate/UserChecklistSelector.vue";
import {
    AvailableInterestsList,
    AvailableSchoolYearList,
    AvailableSubjectsList
} from "@/js/components/userprofile/userprofileupdate/userListing";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";

//important props used to import data from UserProfileSelectionMenu
const props = defineProps({
    leftHeading:{
        type: String,
        required: true
    },
    leftDescription:{
        type: String,
        required: true
    },
    items:{
        type:Array,
        required:true
    },
    rightContentIndex:{
        type: Number,
        required: false,
        default: -1
    },
    rightContentCheckListSelector:{
        type: Number,
        required: false,
        default: -1
    },
    isEditAvatar:{
        type:Object,
        required:false
    },
    userAvatarUrlWithFallback:{
        type:Object,
        required:true
    },
    userAvatar: {
        type: Object,
        required: true
    },
    selectorChecklist:{
        type:Array,
        required:false,
        default: () => []
    },
    selectorCardlist:{
        type:Array,
        required:false,
        default: () => []
    },
    availableYearsListItems:{
        type:String,
        required:true
    },
    availableSubjectsListItems:{
        type:String,
        required:true
    },
    hideInputField:{
        type:Boolean,
        default:true
    },
    hideProfilePicture:{
        type:Boolean,
        default:true
    },
    hideUserCheckListSelector:{
        type:Boolean,
        default:true
    },
    hideUserCardItemSelector:{
        type:Boolean,
        default:true
    },
    sendSaveDataToChild:{
        type:Function,
        default: false
    },
    sendBooleanValueToChild:{
        type:Boolean,
        default: false
    },
    sendSelectedCheckValues:{
        type:String,
        default: false
    },
    sendSelectedCardValues:{
        type:String,
        default: false
    }
})

const uploadAvatar = ref(null)
const handleClickEditAvatar = () => {
    uploadAvatar.value.click()
}





</script>



<template>
    <div class="flex flex-row gap-1 m-6">
        <div class="p-2 w-[30%]">
            <div class="font-museoSans font-semibold text-sm">
                {{ leftHeading }}
            </div>
            <div class="font-museoSans text-sm">
                {{ leftDescription }}
            </div>
        </div>
        <div class="border-2 border-gray-200 h-full p-2 rounded-xl w-[70%]">
            <div
                v-for="(item, index) in items"
                :key="index"
            >
                <div
                    v-if="index !== 0"
                    class="mt-10"
                >
                    {{ item.rightHeading }}
                </div>
                <div v-else>
                    {{ item.rightHeading }}
                </div>
                <div v-if="hideInputField">
                    <!-- Use v-model to bind the input value to the rightContent property of the current item -->
                    <input
                        v-model="item.rightContent"
                        :class="{'h-64': index === rightContentIndex}"
                        class="border-1 border-gray-300 mt-4 rounded-2xl"
                    >
                    <span>
                        <div
                            v-if="!item.rightContent"
                            class="flex items-center flex-row mt-2 text-red-500"
                        >
                            <ErrorIconSmall
                                class="fill-inactive h-5 max-w-none mr-2 shrink-0 stroke-inactive w-5"
                                alt="error icon"
                            />
                            <span class="text-sm">Value is required</span>
                        </div>
                    </span>
                </div>
                <slot name="content" />
            </div>
            <div
                v-if="hideProfilePicture"
                class="flex justify-center h-20 mt-10 roundProfilePicContainer w-20"
            >
                <div
                    class="avatarContainerOverlap relative"
                    @mouseenter="isEditAvatar = !isEditAvatar"
                    @mouseleave="isEditAvatar = !isEditAvatar"
                >
                    <img
                        class="border-8 border-white cursor-pointer h-20 rounded-full userAvatar w-20"
                        :src="userAvatarUrlWithFallback"
                        alt="user profile picture"
                    >
                    <div
                        v-show="isEditAvatar"
                        class="
                            absolute
                            top-2
                            right-2
                            bg-white
                            hover:bg-slate-200
                            border-2
                            border-black
                            cursor-pointer
                            flex
                            justify-center
                            items-center
                            h-6
                            rounded-full
                            w-6
                            "
                        @click="handleClickEditAvatar"
                    >
                        <Edit class="h-[14px] w-[14px]" />
                        <input
                            ref="uploadAvatar"
                            type="file"
                            class="hidden"
                        >
                    </div>
                </div>
            </div>
            <div
                class="
                    -ml-2
                    sm:w-[103.5%]
                    md:w-[103%]
                    lg:w-[103.2%]
                    xl:w-[102.3%]
                    2xl:w-[102%]
                    border-2
                    border-gray-200
                    mt-10
                    w-[105.7%]
                    "
            />
            <div class="flex flex-cols gap-6 mb-2 ml-2 mr-2 mt-4">
                <GenericButton
                    id="cancelBtn"
                    :callback="handleCancelForm"
                    class="!bg-white !h-14 !rounded-xl !text-gray-700 !text-xl border-[1px] border-gray-400 ml-auto p-4 w-28"
                >
                    <template #default>
                        Cancel
                    </template>
                </GenericButton>
                <GenericButton
                    id="submitBtn"
                    :callback="saveDataToDatabase"
                    class="!h-14 !rounded-xl !text-xl p-4 w-28 hover:!bg-main-teal lg:!w-40"
                >
                    <template #default>
                        Save changes
                    </template>
                </GenericButton>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
