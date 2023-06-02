<script setup>
import {ref, computed} from 'vue'
import GenericButton from "@/js/components/button/GenericButton.vue";
import SearchDropdown from 'search-dropdown-vue';
import {serverURL} from "@/js/constants/serverUrl";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";

const props = defineProps({
    siteId: {
        type: Number,
        required: true
    }
})

const { currentUser } = storeToRefs(useUserStore())

const siteStaffList = ref([])
const selectedStaff = ref({})

console.log('site id received is ' + props.siteId)
// fetchAllStaffFromSite/{ssite_id}
axios.get(`${serverURL}/fetchStaffFromSite/${props.siteId}`).then(res => {
    console.log(res.data)
    siteStaffList.value = res.data.filter(staff => staff.id !== currentUser.value.id).map(staff => {
        return {id: staff.id, name: staff.name}
    })
})


// check for nominated staff here/ or schoolsingle
const showNominationDialog = ref(false)

const handleClickNominationButton = () => {
    showNominationDialog.value = !showNominationDialog.value
}

const handleSelectedStaffDropdown = (payload) =>{
    console.log(payload)
    selectedStaff.value = payload
}

</script>

<template>
    <div class="relative flex flex-row  justify-between bg-slate-100 w-1/2">
        <GenericButton
            :callback="handleClickNominationButton"
            type="school"
            class="w-48 ml-10 rounded"
        >
            <div class="">
                Nominate staff to build
            </div>
        </GenericButton>
        <div class="my-3 px-6 searchDropdown form">
            <form
                v-if="siteStaffList.length > 0"
                autocomplete="off"
            >
                <SearchDropdown
                    :options="siteStaffList"
                    @selected="handleSelectedStaffDropdown"
                />
            </form>
            <div v-else>
                Sorry no staff registered under your site
            </div>
        </div>
        <div class=" my-3 listOfNominated px-3">
            <ul>
                <li>One</li>
                <li>Two</li>
                <li>Three</li>
            </ul>
        </div>
    </div>
</template>
