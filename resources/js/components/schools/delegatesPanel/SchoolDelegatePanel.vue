<script setup lang="ts">
import {storeToRefs} from "pinia";
import {computed, onMounted, ref} from 'vue'
import {toast} from "vue3-toastify";

import SchoolDelegateItem from "@/js/components/schools/delegatesPanel/SchoolDelegateItem.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import InfoCircleIcon from "@/js/components/svg/InfoCircleIcon.vue";
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
const loading = ref(true)

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
    } finally {
        loading.value = false
    }
})

const handleClickNominateUser = async (staff) => {
    const result = await schoolService.nominateNewDelegates(props.siteId, props.schoolId, staff.id)
    console.log(result)
    if(result.status === 200){
        nominatedStaffList.value.push(staff)
        toast('Added ' + staff.name + ' as a delegate')
    }
}

// computed variable containing the staff from a site that hasn't been made delegates
const availableStaffList = computed(() => {
    return siteStaffList.value.filter(staff => {
        return !nominatedStaffList.value.some(nominatedStaff => nominatedStaff.id === staff.id)
    })
})


const handleClickDeleteDelegates = async (staff) => {
    // remove from nominated list, add back to staffList ensure the server returns correct
    const result = await schoolService.removeDelegates(props.siteId, props.schoolId,staff.id )
    if(result.status === 200){
        nominatedStaffList.value = nominatedStaffList.value.filter(nominatedStaff => nominatedStaff.id !== staff.id)
        toast('Removed ' + staff.name + ' from delegate list')

    }
}
</script>

<template>
    <div
        class="DelegationPanelInnerContainer flex flex-col rounded-xl w-full"
    >
        <div class="font-semibold mb-2 text-genericDark text-lg">
            Nominate staff from your site to build this page
        </div>
        <div
            v-if="availableStaffList.length"
            class="DelegationPanelAvailableSection bg-gray-100 mb-4 px-2 rounded-xl"
        >
            <div class="font-semibold pt-2 text-gray-400 text-sm uppercase">
                <span class="flex items-center flex-row gap-2">Available
                    <info-circle-icon
                        v-tippy="{content: 'Assigned User will be able to edit the page. Click on their name to make them a delegate', arrow: true, theme: 'light'}"
                        class="hover:stroke-main-lightTeal"
                    /></span>
                <div
                    v-if="!loading"
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
                <div
                    v-else
                    class="pb-4"
                >
                    <Loader
                        loader-type="inline"
                        loader-message-class="text-lg font-semibold pb-4"
                    />
                </div>
            </div>
        </div>
        <div
            v-if="nominatedStaffList.length"
            class="DelegationPanelAssignedSection bg-gray-100 mb-4 px-2 rounded-xl"
        >
            <div class="font-semibold pt-2 text-gray-400 text-sm uppercase">
                <span class="flex items-center flex-row gap-2">Assigned <info-circle-icon
                    v-tippy="{content: 'These users can edit the page. Click on their name to remove them from delegate list', arrow: true, theme: 'light'}"
                    class="hover:stroke-main-lightTeal"
                /></span>
                <div
                    v-if="!loading"
                    v-dragscroll
                    class="bg-gray-100 flex flex-col innerList max-h-[300px] mt-1 overflow-y-scroll w-full hover:cursor-grab"
                >
                    <template
                        v-for="(staff,index) in nominatedStaffList"
                        :key="index"
                    >
                        <SchoolDelegateItem
                            :click-callback="handleClickDeleteDelegates"
                            :user="staff"
                        />
                    </template>
                </div>
                <div
                    v-else
                    class="pb-4"
                >
                    <Loader
                        loader-type="inline"
                        loader-message-class="text-lg font-semibold pb-4"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

