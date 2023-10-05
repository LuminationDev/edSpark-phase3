<script setup lang="ts">
/**
 * IMPORT DEPENDENCIES
 */
import axios from 'axios'
import {storeToRefs} from "pinia";
import {computed, onBeforeMount, Ref, ref} from 'vue'
import {useRoute} from 'vue-router';

import BaseBreadcrumb from "@/js/components/bases/BaseBreadcrumb.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";
import SchoolNominationButton from "@/js/components/schools/SchoolNominationButton.vue";
import SchoolNotAvailable from "@/js/components/schools/SchoolNotAvailable.vue";
import SchoolContent from "@/js/components/schoolsingle/SchoolContent.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {parseToJsonIfString} from "@/js/helpers/jsonHelpers";
import {isObjectEmpty} from "@/js/helpers/objectHelpers";
import {schoolDataFormDataBuilder} from "@/js/helpers/schoolDataHelpers";
import {schoolService} from "@/js/service/schoolService";
import {useSchoolsStore} from "@/js/stores/useSchoolsStore";
import {useUserStore} from "@/js/stores/useUserStore";
import {SchoolDataType} from "@/js/types/SchoolTypes";


const route = useRoute();
const imageURL = import.meta.env.VITE_SERVER_IMAGE_API

const schoolContent: Ref<SchoolDataType | null> = ref(null)
const colorTheme = ref('teal') // default color theme
const showSchoolNotAvailable = ref(false)
const showRetryCreateSchool = ref(false)

// ref to hold images from editing
const logoStorage = ref(null)
const coverImageStorage = ref(null)

// ref to handle tooltip
const toggleTooltip = ref(false);
const tooltipIndex = ref(null);

const handleToggleTooltip = (index) => {
    toggleTooltip.value = !toggleTooltip.value;
    tooltipIndex.value = index;
}

const {newSchool}: { newSchool: Ref<SchoolDataType> } = storeToRefs(useSchoolsStore())
const {currentUser} = storeToRefs(useUserStore())

onBeforeMount(async () => {
    const currentSchoolName = route.params.name
    schoolService.getSchoolDataForDash(currentUser.value.id, currentUser.value.site_id).then(res =>{
        console.log(res)
    }).catch(err =>{
        if(err.includes('403')){
            console.log()
        }
        console.error('cause of error is ' + err)
    })
    fetchSchoolByNameAsync(currentSchoolName).then( () =>{

    }).catch(err => {
        if(err.toString().includes('404')){
            // school not found here
            // bit this is schoolSingle, how can you go inside school single but school not found? directly from URL

        }
    })
    //scenario where school is not created but Principal has entered school information in the firstVisitForm

    // if (newSchool.value.schoolName &&
    //     Object.keys(schoolContent.value).length <= 0 &&
    //     (currentUser.value.role === "Principal" || currentUser.value.role === "SCHLDR")) {
    //     // TODO: check if newSchool.schoolName is valid inside site databasr
    //     triggerCreateNewSchoolFromSchoolStore().then(res => {
    //         console.log('fetching school after triggeringCreation')
    //         fetchSchoolByNameAsync(currentSchoolName)
    //     })
    // } else {
    //     console.log('No new school will be created')
    //     showSchoolNotAvailable.value = true
    // }
})


const fetchSchoolByNameAsync = (schoolName) => {
    return schoolService.fetchSchoolByName(schoolName).then(res => {
        console.log('Found the school. populating data now inside SchoolSingle')
        const filteredSchool = res.data
        schoolContent.value = parseToJsonIfString(filteredSchool)
        schoolContent.value.content_blocks = parseToJsonIfString(schoolContent.value.content_blocks)
        schoolContent.value.tech_used = parseToJsonIfString(schoolContent.value.tech_used)
        schoolContent.value.cover_image = schoolContent.value.cover_image.replace("/\\/g", "")
        schoolContent.value.logo = schoolContent.value.logo.replace("/\\/g", "")
        if (filteredSchool.metadata) {
            console.log(filteredSchool.metadata)
            // here is some meta filtering coded
            const colorThemeMeta = schoolContent.value['metadata'].filter(meta => meta['schoolmeta_key'] === 'school_color_theme')
            if (colorThemeMeta.length > 0) {
                colorTheme.value = colorThemeMeta[0]['schoolmeta_value']
            }
        }
    }).catch(async err => {
        console.log(err.message + ' Inside fetchSchoolByName')
        schoolContent.value = {}
    });
}

const triggerCreateNewSchoolFromSchoolStore = () => {

    console.log('TriggeredCreateNewSchoolFromSchoolStore')
    // create a site_id field inside site in order for the helper function to work
    newSchool.value.site['site_id'] = newSchool.value.site['site_id'] || newSchool.value.site.id
    const processedSchoolData = {
        site: newSchool.value.site,
        owner: {
            owner_id: currentUser.value.id
        },
        name: newSchool.value.schoolName,
    }
    const schoolFormData = schoolDataFormDataBuilder(processedSchoolData)
    schoolFormData.append('logo', newSchool.value.logoUrl)
    schoolFormData.append('cover_image', newSchool.value.coverImageUrl)
    return axios({
        method: "post",
        url: API_ENDPOINTS.SCHOOL.CREATE_SCHOOL,
        data: schoolFormData,
        headers: {"Content-Type": "multipart/form-data"},
    }).then(res => {
        console.log(res.data)
        console.log('School Created with FirstVisitData')
        // need to populate schoolContent.value
        showRetryCreateSchool.value = false
    }).catch(e => {
        console.log('there has been an issue while trying to create school from newSchool from schoolstore')
        showSchoolNotAvailable.value = true
        showRetryCreateSchool.value = true
    })
}

const handleSaveNewSchoolInfo = async (content_blocks, tech_used) => {
    // Copy current schoolData and replace content_blocks and tech_used
    const schoolData = _.cloneDeep(schoolContent.value)
    schoolData.content_blocks = content_blocks
    schoolData.tech_used = tech_used
    const newUpdatedSchoolFormData = schoolDataFormDataBuilder(schoolData)

    if (logoStorage.value) {
        newUpdatedSchoolFormData.append('logo', logoStorage.value)
    } else {
        newUpdatedSchoolFormData.append('logo', schoolData.logo)
    }
    if (coverImageStorage.value) {
        newUpdatedSchoolFormData.append('cover_image', coverImageStorage.value)
    } else {
        newUpdatedSchoolFormData.append('cover_image', schoolData.cover_image)
    }

    const schoolMetadata = {school_color_theme: colorTheme.value}
    newUpdatedSchoolFormData.append('metadata', JSON.stringify(schoolMetadata))
    console.log(schoolMetadata)
    await axios({
        url: API_ENDPOINTS.SCHOOL.UPDATE_SCHOOL,
        method: 'post',
        data: newUpdatedSchoolFormData,
        headers: {"Content-Type": "multipart/form-data"}
    }).then(res => {
        // if the created post status is published,
        // assign school info with newest data that has been saved succesfully to trigger update
        if (res.data.data.status === 'Published') {
            schoolContent.value = res.data.data
            schoolContent.value.content_blocks = parseToJsonIfString(schoolContent.value.content_blocks)
            schoolContent.value.tech_used = parseToJsonIfString(schoolContent.value.tech_used)
        }
    }).catch(err => {
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
const handleReceivePhotoFromContent = (type, file) => {
    switch (type) {
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

// Cover image loading workaround
const isCoverImageLoaded = ref(false)

const coverImageLink = computed(() => {
    if (schoolContent.value['cover_image']) {
        console.log('loaded');
        return schoolContent.value['cover_image'].replace(/\\\//g, "/");
    } else {
        console.log('noloaddddd');
        return;
    }
});

const handleCoverImageLoaded = () => {
    isCoverImageLoaded.value = true
}


// Submenu specific codes
const schoolSubmenu = [
    {
        displayText: 'Details',
        value: 'detail'
    },
    {
        displayText: "What's New",
        value: 'new'
    },
    {
        displayText: 'Contact',
        value: 'contact'
    }
]
const activeSubmenu = ref(schoolSubmenu[0]['value'])
const handleChangeSubmenu = (value) => {
    activeSubmenu.value = value
}
const isSchoolContentPopulated = computed(() => {
    return !!schoolContent.value && !isObjectEmpty(schoolContent.value)
})

// End of submenu specific code  plus @emit-active-tab-to-specific-page in BaseSingle

</script>
<template>
    <div v-if="isSchoolContentPopulated">
        <div class="-mt-[140px] flex flex-col">
            <img
                class="hidden"
                aria-hidden="true"
                :src="`${imageURL}/${schoolContent.cover_image}`"
                alt="School background preload image"
            >
            <!-- @load="handleCoverImageLoaded" -->
            <BaseSingle
                content-type="school"
                @emit-active-tab-to-specific-page="handleChangeSubmenu"
            >
                <template #hero="{emitFromSubmenu}">
                    <BaseHero
                        :background-url="coverImageLink"
                        :swoosh-color-theme="colorTheme"
                    >
                        <template #breadcrumb>
                            <BaseBreadcrumb
                                :child-page="schoolContent.name"
                                parent-page="schools"
                                :color-theme="colorTheme"
                                class="mt-[100px]"
                            />
                        </template>
                        <template #titleText>
                            <div class="SchoolHeroContentContainer flex flex-row w-full">
                                <div class="flex flex-row w-full">
                                    <div class="flex flex-col">
                                        <h1 class="font-bold text-white">
                                            {{ schoolContent.name }}
                                        </h1>
                                        <div
                                            class="flex flex-row gap-4 mb-4 place-items-center"
                                        >
                                            <div
                                                v-for="(tech, index) in schoolContent.tech_used"
                                                :key="index"
                                                class="
                                                    cursor-pointer
                                                    hidden
                                                    relative
                                                    w-6
                                                    md:!w-14
                                                    lg:!block
                                                    "
                                            >
                                                <div
                                                    @mouseenter="handleToggleTooltip(index)"
                                                    @mouseleave="handleToggleTooltip(index)"
                                                >
                                                    <SchoolTechIconGenerator
                                                        :tech-name="tech.name"
                                                        class="
                                                            cursor-pointer
                                                            m-2
                                                            min-w-[30px]
                                                            pr-1
                                                            relative
                                                            w-8
                                                            md:!min-w-[60px]
                                                            md:!pr-4
                                                            "
                                                    />
                                                    <div
                                                        v-if="toggleTooltip && tooltipIndex === index"
                                                        class="
                                                            absolute
                                                            bg-main-navy
                                                            border-l-[3px]
                                                            border-white
                                                            px-[24px]
                                                            py-[18px]
                                                            shadow-xl
                                                            w-[450px]
                                                            "
                                                    >
                                                        <h3
                                                            class="
                                                                font-semibold
                                                                text-[20px]
                                                                text-white
                                                                "
                                                        >
                                                            {{ tech.name }}
                                                        </h3>
                                                        <p
                                                            class="
                                                                font-normal
                                                                text-sm
                                                                text-white
                                                                xl:!text-base
                                                                "
                                                        >
                                                            {{ tech.description }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-center items-center h-40 w-40">
                                            <img
                                                :src="`${imageURL}/${schoolContent.logo}`"
                                                :alt="`${schoolContent.name} logo`"
                                                class="max-h-full object-contain w-full"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #submenu>
                            <div class="SchoolSubmenu cursor-pointer flex flex-row gap-2 z-40 md:!gap-4">
                                <BaseSingleSubmenu
                                    :emit-to-base="emitFromSubmenu"
                                    :menu-array="schoolSubmenu"
                                    :active-subpage="activeSubmenu"
                                />
                            </div>
                        </template>
                    </BaseHero>
                </template>
                <template #content>
                    <div class="flex flex-col mt-10 w-full xl:!mt-20">
                        <SchoolContent
                            :school-content="schoolContent"
                            :color-theme="colorTheme"
                            :active-submenu="activeSubmenu"
                            @send-info-to-school-single="handleSaveNewSchoolInfo"
                            @send-color-to-school-single="handleChangeColorTheme"
                            @send-photo-to-school-single="handleReceivePhotoFromContent"
                        >
                            <template #additionalContentActions>
                                <SchoolNominationButton
                                    v-if="schoolContent['site']['site_id']"
                                    :site-id="schoolContent['site']['site_id']"
                                    :school-id="schoolContent['school_id']"
                                />
                            </template>
                        </SchoolContent>
                    </div>
                </template>
            </BaseSingle>
        </div>
    </div>
    <div
        v-else-if="!isSchoolContentPopulated && showSchoolNotAvailable"
        class="flex justify-center items-center flex-col h-36 mt-[10vh]"
    >
        <SchoolNotAvailable />
    </div>
    <div
        v-else-if="!isSchoolContentPopulated && showRetryCreateSchool"
        class="flex justify-center items-center flex-col h-36 mt-[10vh]"
    >
        <GenericButton
            :callback="triggerCreateNewSchoolFromSchoolStore"
            type="school"
        >
            <div class="font-bold px-2 py-2 text-md">
                Retry create school
            </div>
        </GenericButton>
    </div>

    <div
        v-else
        class="mt-20"
    >
        <Loader
            :loader-color="'#0072DA'"
            :loader-message="'School loading'"
        />
    </div>
</template>
