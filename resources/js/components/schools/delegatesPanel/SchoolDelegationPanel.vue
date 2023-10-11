<script setup lang="ts">
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from 'vue'

import SchoolDelegateItem from "@/js/components/schools/delegatesPanel/SchoolDelegateItem.vue";
import {imageURL} from "@/js/constants/serverUrl";
import {schoolService} from "@/js/service/schoolService";
import {useUserStore} from "@/js/stores/useUserStore";

export type delegatesUser = {
    id: number;
    userAvatar: string;
    display_name: string;
    name: string;
    role: string
}


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
const nominatedStaffList = ref([])


onMounted(async () => {
    try {
        const [staffList, delegates] = await Promise.all([
            schoolService.getAllStaffBySiteId(props.siteId, currentUser.value.id),
            schoolService.getSchoolDelegates(props.siteId, props.schoolId, currentUser.value.id)
        ]);

        siteStaffList.value = staffList;
        nominatedStaffList.value = delegates;
    } catch (error) {
        console.error("Error fetching data:", error);
    }
})

const handleClickNominateUser = async (staffId) => {
    const result = await schoolService.nominateNewDelegates(props.siteId, props.schoolId, staffId)
    console.log(result)
}

// computed variable containing the staff from a site that hasn't been made delegates
const availableStaffList = computed(() => {
    return siteStaffList.value.filter(staff => {
        return !nominatedStaffList.value.some(nominatedStaff => nominatedStaff.id === staff.id)
    })
})


const handleClickDeleteDelegates = async (staffId) => {
    // remove from nominated list, add back to staffList ensure the server returns correct
}
</script>

<template>
    <div
        class="DelegationPanelInnerContainer flex flex-col rounded-xl w-full"
    >
        <div class="DelegationPanelAvailableSection bg-gray-100 mb-4 px-2 rounded-xl">
            <div class="font-semibold pt-2 text-gray-400 text-sm uppercase">
                Available
                <div
                    v-dragscroll
                    class="bg-gray-100 flex flex-col innerList max-h-[300px] mt-1 overflow-y-scroll w-full hover:cursor-grab"
                >
                    <template
                        v-for="(staff,index) in availableStaffList"
                        :key="index"
                    >
                        <SchoolDelegateItem
                            :click-callback="handleClickNominateUser"
                            :user="staff"
                        />
                    </template>
                </div>
            </div>
        </div>
        <div class="DelegationPanelAssignedSection bg-gray-100 mb-4 px-2 rounded-xl">
            <div class="font-semibold pt-2 text-gray-400 text-sm uppercase">
                Assigned
                <div
                    v-dragscroll
                    class="bg-gray-100 flex flex-col innerList max-h-[300px] mt-1 overflow-y-scroll w-full hover:cursor-grab"
                >
                    <template
                        v-for="(staff,index) in nominatedStaffList"
                        :key="index"
                    >
                        <SchoolDelegateItem
                            :click-callback="handleClickNominateUser"
                            :user="staff"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

