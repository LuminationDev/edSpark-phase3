<script setup>
import axios from "axios";
import {storeToRefs} from "pinia";
import SearchDropdown from 'search-dropdown-vue';
import {computed, onMounted,ref} from 'vue'

import GenericButton from "@/js/components/button/GenericButton.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
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

onMounted(() => {
    axios.get(`${API_ENDPOINTS.SCHOOL.FETCH_STAFF_FROM_SITE}${props.siteId}`).then(res => {
        siteStaffList.value = res.data.filter(staff => staff.id !== currentUser.value.id).map(staff => {
            return {id: staff.id, name: staff.name}
        })
    })

    const getAllNominatedUsers = async () => {
        await axios({
            method: "POST",
            url: API_ENDPOINTS.SCHOOL.GET_NOMINATED_USER_FROM_SCHOOL,
            data: {
                "site_id": props.siteId,
                "school_id": props.schoolId,
                "user_id": currentUser.value.id
            }
        }).then(res => {
            console.log(res.data)
            if (res.data.status === 200) {
                const keys = Object.keys(res.data.result)
                keys.forEach((key, index) => {
                    nominatedStaffList.value.push({
                        id: key,
                        name: res.data.result[key]
                    })
                })
            }
        })
    }
    getAllNominatedUsers()


})

// check for nominated staff here/ or schoolsingle
const showNominationDialog = ref(false)

const handleClickNominationButton = () => {
    showNominationDialog.value = !showNominationDialog.value
}

const handleSelectedStaffDropdown = (payload) => {
    selectedStaff.value = payload
    if (payload.id) {
        axios({
            method: "POST",
            url: API_ENDPOINTS.SCHOOL.NOMINATE_USER_FOR_SCHOOL,
            data: {
                "site_id": props.siteId,
                "school_id": props.schoolId,
                "user_id": currentUser.value.id,
                'nominated_user_id': payload.id
            }
        }).then(res => {
            nominatedStaffList.value.push({
                id: payload.id,
                name: payload.name
            })

        })

    }
    else{
        console.log('payload.id is missing')
    }
}

const doesSiteStaffListExists = computed(() => {
    return siteStaffList.value.length > 0;
})

const handleDeleteNominatedUser = async (staffId) => {
    console.log('deleted nominated user ' + staffId)
    if (staffId) {
        await axios({
            method: "POST",
            url: API_ENDPOINTS.SCHOOL.DELETE_NOMINATED_USER,
            data: {
                "site_id": props.siteId,
                "school_id": props.schoolId,
                "user_id": currentUser.value.id,
                'nominated_id_delete': staffId
            }
        }).then(res => {
            if (res.data.status === 200) {
                nominatedStaffList.value = nominatedStaffList.value.filter(staff => staff.id !== staffId)
            }
            console.log(res.data.result)
        });

    } else {
        console.log('missing staffID when handleDeleteNominatedUser is called')
    }
}

</script>

<template>
    <div class="flex justify-around justify-center items-center flex-row relative w-full">
        <div class="flex items-center h-full w-1/2">
            <GenericButton
                :callback="handleClickNominationButton"
                type="school"
                class="px-2 w-48"
                :disabled="!doesSiteStaffListExists"
            >
                <div class="">
                    Nominate staff to build
                </div>
            </GenericButton>
        </div>
        <div class="form my-3 px-1 py-2 searchDropdown w-1/2">
            <form
                v-if="doesSiteStaffListExists"
                autocomplete="off"
            >
                <SearchDropdown
                    :options="siteStaffList"
                    :max-item="15"
                    @selected="handleSelectedStaffDropdown"
                />
            </form>
            <div v-else>
                Sorry no staff registered under your site
            </div>
        </div>
    </div>
    <div
        v-if="doesSiteStaffListExists"
        class="flex flex-col nominatedUsers px-6 text-lg"
    >
        <p>Nominated Staff:</p>
        <p class="text-sm">
            Click on the name to remove nomination
        </p>
        <ul class="list-disc unorderedListOfNominatedUsers">
            <div
                v-for="(staff,index) in nominatedStaffList"
                :key="index"
                class="flex flex-row"
            >
                <li
                    class="hover:cursor-pointer hover:font-semibold"
                >
                    {{ staff.name }} {{ `(${staff.id})` }}
                </li>
                <GenericButton
                    type="plain"
                    class="flex items-center ml-4 text-sm hover:!text-red-600 hover:cursor-pointer"
                    :callback="() => handleDeleteNominatedUser(staff.id)"
                >
                    Delete
                </GenericButton>
            </div>
        </ul>
    </div>
</template>
