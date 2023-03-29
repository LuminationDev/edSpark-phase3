<script setup>
    /**
     * IMPORT DEPENDENCIES
     */
import { useRouter, useRoute } from 'vue-router';
import {ref} from 'vue'
import axios from 'axios'
import {parseToJsonIfString} from "@/js/helpers/jsonHelpers";
import {schoolDataFormDataBuilder, printOutFormData} from "@/js/helpers/schoolDataHelpers";

/**
 * IMPORT COMPONENTS
 */
import SchoolsProfile from '../components/schools/SchoolsProfile.vue';
import SchoolContent from "@/js/components/schoolsingle/SchoolContent.vue";
/**
 * IMPORT SVGS
 */
import SchoolsSubMenu from '../components/svg/SchoolsSubMenu.vue';
import ChevronRight from '../components/svg/ChevronRight.vue';
import {onBeforeMount} from "vue";

const route = useRoute();
const router = useRouter();
const serverURL = import.meta.env.VITE_SERVER_URL_API
const imageURL = import.meta.env.VITE_SERVER_IMAGE_API


const urlOrigin = window.location.origin
const breadCrumbPrev = 'Schools'
const breadCrumbName = route.params.name

const schoolContent = ref({})
const colorTheme = ref('amber') // default color theme

// ref to hold images from editing
const logoStorage = ref(null)
const coverImageStorage = ref(null)


onBeforeMount( async () =>{
    // TODO Erick - Replace with get one school instead of all then filter.
    await axios.get(`${serverURL}/fetchAllSchools`).then(res => {
        const filteredSchool = res.data.filter(school => school.name === route.params.name.replace('%20', ' ' ))[0]
        schoolContent.value = parseToJsonIfString(filteredSchool)
        /**
         * Parse content of SchoolContent upon receiving from server.
         * avoid further processing down the components
         */
        schoolContent.value.content_blocks = parseToJsonIfString(schoolContent.value.content_blocks)
        schoolContent.value.tech_used = parseToJsonIfString(schoolContent.value.tech_used)
        schoolContent.value.cover_image = schoolContent.value.cover_image.replace("/\\/g", "")
        if(filteredSchool.metadata){
            const colorThemeMeta = schoolContent.value['metadata'].filter(meta => meta['schoolmeta_key'] === 'school_color_theme')
            colorTheme.value = colorThemeMeta[0]['schoolmeta_value']
        }
    }).catch(err => {
        console.log(err)
    })
})
    
const handleSaveNewSchoolInfo = async (content_blocks, tech_used) => {
    /**
     * Copy current schoolData and replace content_blocks and tech_used
     */
    const schoolData = _.cloneDeep(schoolContent.value)
    schoolData.content_blocks = content_blocks
    schoolData.tech_used  = tech_used
    const newUpdatedSchoolFormData = schoolDataFormDataBuilder(schoolData)

    if(logoStorage.value){
        newUpdatedSchoolFormData.append('logo', logoStorage.value)
    } else{
        newUpdatedSchoolFormData.append('logo', schoolData.logo)
    }
    if(coverImageStorage.value){
        newUpdatedSchoolFormData.append('cover_image', coverImageStorage.value)
    } else{
        newUpdatedSchoolFormData.append('cover_image', schoolData.cover_image)
    }



    const schoolMetadata = {school_color_theme : colorTheme.value}
    newUpdatedSchoolFormData.append('metadata', schoolMetadata)
    printOutFormData(newUpdatedSchoolFormData)
    await axios({
        url: `${serverURL}/updateSchool`,
        method: 'post',
        data: newUpdatedSchoolFormData,
        headers: {"Content-Type" : "multipart/form-data"}
    }).then(res =>{
        // assign school info with newest data that has been saved succesfully to trigger update
        console.log(res.data.data)
        schoolContent.value = res.data.data
        // parse JSON after receiving data from backend. can be done better
        // can consider computed function
        schoolContent.value.content_blocks = parseToJsonIfString(schoolContent.value.content_blocks)
        schoolContent.value.tech_used = parseToJsonIfString(schoolContent.value.tech_used)
    }).catch(err =>{
        console.log(err)
        console.log('Something wrong while attempting to post ')

    })
}

const handleChangeColorTheme = (newColor) => {
    console.log('received command to swap color to -> ' + 'newColor')
    colorTheme.value = newColor
}
/**
 * Handle Event emitted from children containing type {logo,coverImage} and Image file
 * @param {String} type
 * @param {File} file
 */
const handleReceivePhotoFromContent = (type,file) => {
    switch(type){
    case 'logo':
        console.log('received logo')
        logoStorage.value = file
        break;
    case 'coverImage':
        console.log('received cover Image')
        coverImageStorage.value = file
        break;
    default:
        console.log('received unknown type image')
        break;
    }
}
</script>

<template>
    <div class="-mt-[140px] flex flex-col ">
        <SchoolsProfile>
            <template #hero>
                <div
                    class="px-[48px]  h-[680px] w-full bg-center bg-no-repeat bg-cover"
                    :class="`bg-[url(${imageURL}/${schoolContent.cover_image})]`"
                >
                    <div class="h-full w-full grid grid-cols-12">
                        <div class="col-span-7 flex mt-[190px]">
                            <div class="flex flex-row gap-2 h-fit place-items-center">
                                <router-link to="/">
                                    <p class="text-[14px] text-white hover:text-[#44B8F3]">
                                        Home
                                    </p>
                                </router-link>
                                <!-- TODO: Breadcrumb builder -- gotta be smart -->
                                <ChevronRight />
                                <router-link to="/schools">
                                    <p class="text-[14px] text-white hover:text-[#44B8F3]">
                                        {{ breadCrumbPrev }}
                                    </p>
                                </router-link>
                                <ChevronRight />
                                <p class="text-[14px] text-[#44B8F3]">
                                    {{ breadCrumbName }}
                                </p>
                            </div>
                        </div>
                        <div class="col-span-5" />
                    </div>
                </div>
            </template>
        </SchoolsProfile>

        <div class="-mt-[180px] mb-20">
            <SchoolsSubMenu :color-theme="colorTheme" />
        </div>
        <div class="flex flex-row w-full">
            <div
                v-if="Object.keys(schoolContent).length > 1"
                class="school-content py-2 px-10 flex w-full"
            >
                <SchoolContent
                    :school-content="schoolContent"
                    :color-theme="colorTheme"
                    @send-info-to-school-single="handleSaveNewSchoolInfo"
                    @send-color-to-school-single="handleChangeColorTheme"
                    @send-photo-to-school-single="handleReceivePhotoFromContent"
                />
            </div>
        </div>
    </div>
</template>
