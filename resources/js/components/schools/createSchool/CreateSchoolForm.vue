<script setup>
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {onBeforeMount, ref, reactive} from "vue";
import axios from "axios";
import SearchDropdown from 'search-dropdown-vue'
import GenericButton from "@/js/components/button/GenericButton.vue";
import {
    defaultSchoolTechLandscape,
    defaultSchoolTechList,
    defaultSchoolContent,
    defaultSchoolPedagogicalApproaches
} from "@/js/constants/schoolContentDefault";
import {useUserStore} from "@/js/stores/useUserStore";
import {storeToRefs} from "pinia";

const serverURL = import.meta.env.VITE_SERVER_URL_API
const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)


let allSitesObject = ref([])
let logoFile = ref(null)
let coverImageFile = ref(null)


const state = reactive({
    siteId: '',
    name: '',
    ownerId: '',
    logo: '',
    cover_image: ''

})

onBeforeMount(async () => {
    await axios.get(API_ENDPOINTS.SCHOOL.FETCH_ALL_SITES).then((res) => {
        res.data.map(site => {
            const tempObject = {
                id: site.id,
                name: site.site_name
            }
            allSitesObject.value.push(tempObject)
        })
    })
})
const emits = defineEmits(['finishCreateSchool'])

const handleSubmitButton = async () => {
    // TODO - refactor with helper SchoolDataFormDataBuilder
    let schoolData = new FormData();
    schoolData.append('site_id',state.siteId)
    schoolData.append('owner_id', 1)
    schoolData.append('name', state.name)
    schoolData.append('content_blocks', JSON.stringify(defaultSchoolContent))
    schoolData.append('logo', logoFile.value)
    schoolData.append('cover_image', coverImageFile.value)
    schoolData.append('tech_used', JSON.stringify(defaultSchoolTechList))
    schoolData.append('pedagogical_approaches', JSON.stringify(defaultSchoolPedagogicalApproaches))
    schoolData.append('tech_landscape', JSON.stringify(defaultSchoolTechLandscape))

    await axios({
        method: "post",
        url: API_ENDPOINTS.SCHOOL.CREATE_SCHOOL,
        data: schoolData,
        headers: { "Content-Type": "multipart/form-data" },
    }).then(res => {
        console.log(res.data)
        emits('finishCreateSchool')
    })
}

const handleSelected = (data) => {
    state.name = data.name
    state.siteId = data.id
}

const handleLogoUpload = (event) =>{
    logoFile.value = event.target.files[0]
}

const handleCoverImageUpload = (event) => {
    coverImageFile.value = event.target.files[0]
}

</script>
<template>
    <div class="border-2 createSchoolContainer mx-4 my-2 px-4 py-2 rounded-lg">
        <div class="font-semibold text-center text-xl">
            Create School Form
        </div>
        <div class="mb-5">
            <SearchDropdown
                name="school-selection"
                placeholder="Select school name"
                :options="allSitesObject"
                @selected="handleSelected"
            />
        </div>
        <label
            for="logoUpload"
            class="my-2"
        > Upload your school logo</label>
        <input
            id="logoUpload"
            ref="logoFile"
            type="file"
            class="mb-5"
            @change="handleLogoUpload"
        >
        <label
            for="coverImageUpload"
            class="my-2"
        > Upload your school cover image</label>
        <input
            id="coverImageUpload"
            ref="coverImageFile"
            class="mb-5"
            type="file"
            @change="handleCoverImageUpload"
        >
        <GenericButton :callback="handleSubmitButton">
            Create School
        </GenericButton>
    </div>
</template>