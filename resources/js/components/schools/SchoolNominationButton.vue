<script setup>
import {ref, computed, onMounted} from 'vue'
import GenericButton from "@/js/components/button/GenericButton.vue";
import SearchDropdown from 'search-dropdown-vue';
import {serverURL} from "@/js/constants/serverUrl";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import axios from "axios";

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
    axios.get(`${serverURL}/fetchStaffFromSite/${props.siteId}`).then(res => {
        siteStaffList.value = res.data.filter(staff => staff.id !== currentUser.value.id).map(staff => {
            return {id: staff.id, name: staff.name}
        })
    })

    const getAllNominatedUsers = async () => {
        await axios({
            method: "POST",
            url: `${serverURL}/getNominatedUsersFromSchool`,
            data: {
                "site_id": props.siteId,
                "school_id": props.schoolId,
                "user_id": currentUser.value.id
            }
        }).then(res => {
            console.log(res.data)
            if (res.data.status === 200) {
                let keys = Object.keys(res.data.result)
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
            url: `${serverURL}/nominateUserForSchool`,
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
            url: `${serverURL}/deleteNominatedUser`,
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
    <div class="relative flex flex-row justify-around w-full items-center justify-center">
        <div class="w-1/2 h-full flex items-center">
            <GenericButton
                :callback="handleClickNominationButton"
                type="school"
                class="w-48 !rounded px-2"
                :disabled="!doesSiteStaffListExists"
            >
                <div class="">
                    Nominate staff to build
                </div>
            </GenericButton>
        </div>
        <div class="my-3 px-1 py-2 w-1/2 searchDropdown form">
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
    <div class="nominatedUsers flex flex-col px-6 text-lg">
        <p>Nominated Staff:</p>
        <p class="text-sm">
            Click on the name to remove nomination
        </p>
        <ul class="unorderedListOfNominatedUsers list-disc ">
            <div
                v-for="(staff,index) in nominatedStaffList"
                :key="index"
                class="flex flex-row"
            >
                <li
                    class="hover:font-semibold hover:cursor-pointer "
                >
                    {{ staff.name }} {{ `(${staff.id})` }}
                </li>
                <GenericButton
                    type="plain"
                    class="ml-4 text-sm items-center flex hover:!text-red-600 hover:cursor-pointer"
                    :callback="() => handleDeleteNominatedUser(staff.id)"
                >
                    Delete
                </GenericButton>
            </div>
        </ul>
    </div>
</template>
