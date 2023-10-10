<script setup lang="ts">
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from 'vue'

import {imageURL} from "@/js/constants/serverUrl";
import {schoolService} from "@/js/service/schoolService";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    siteId: {
        type: Number,
        required: true
    },
    schoolId: {
        type: Number,
        required: true
    }
})
const {currentUser} = storeToRefs(useUserStore())

const siteStaffList = ref([])
const selectedStaff = ref({})
const nominatedStaffList = ref([])


onMounted(async () =>{
    siteStaffList.value = await schoolService.getAllStaffBySiteId(props.siteId, currentUser.value.id)
    if(siteStaffList.value.length){
        nominatedStaffList.value = await schoolService.getSchoolDelegates(props.siteId, props.schoolId, currentUser.value.id)
    }

})
</script>

<template>
    <div class="DelegationPanelInnerContainer border-[1px] border-gray-100 flex flex-col rounded-lg w-full">
        <div class="DelegationPanelAvailableSection bg-gray-100 mb-4 px-2">
            <div class="mb-4 text-gray-400 text-sm uppercase">
                Available
                <div class="bg-gray-100 flex flex-col innerList max-h-[300px] overflow-y-scroll w-full">
                    <div
                        v-for="(staff,index) in siteStaffList"
                        :key="index"
                        class="bg-white flex flex-row innerListItem mb-2 px-2 rounded-xl w-24 w-full"
                    >
                        <div class="basis-1/4 containePictureProfile">
                            <img
                                :src="`${imageURL}/${staff.userAvatar}`"
                                alt="photo"
                                class="h-20 object-center object-cover rounded-full w-20"
                            >
                        </div>
                        <div class="basis-3/4 flex flex-col nameAndTitle">
                            <div class="Name font-semibold">
                                {{ staff.name }}
                            </div>
                            <div class="font-semibold roleTitle">
                                {{ staff.role }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="DelegationPanelAssignedSection bg-gray-100 mb-4 px-2">
            <div class="text-gray-400 text-sm uppercase">
                Assigned
            </div>
        </div>
    </div>
</template>

