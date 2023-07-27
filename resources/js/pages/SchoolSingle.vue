<script setup>
/**
 * IMPORT DEPENDENCIES
 */
import axios from 'axios'
import {useRouter, useRoute} from 'vue-router';
import {onBeforeMount, computed, ref} from 'vue'
import {storeToRefs} from "pinia";
import {parseToJsonIfString, schoolContentArrParser} from "@/js/helpers/jsonHelpers";
import {schoolDataFormDataBuilder, printOutFormData} from "@/js/helpers/schoolDataHelpers";
/**
 * IMPORT COMPONENTS
 */
import SchoolContent from "@/js/components/schoolsingle/SchoolContent.vue";
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import SchoolNotAvailable from "@/js/components/schools/SchoolNotAvailable.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
/**
 * IMPORT SVGS
 */
import {useSchoolsStore} from "@/js/stores/useSchoolsStore";
import ChevronRight from '../components/svg/ChevronRight.vue';
import {isObjectEmpty} from "@/js/helpers/objectHelpers";
import {useUserStore} from "@/js/stores/useUserStore";
import SchoolNominationButton from "@/js/components/schools/SchoolNominationButton.vue";
import SchoolContact from "@/js/components/schoolsingle/SchoolContact.vue";
import SchoolWhatsNew from "@/js/components/schoolsingle/SchoolWhatsNew.vue";

const route = useRoute();
const router = useRouter();
const serverURL = import.meta.env.VITE_SERVER_URL_API
const imageURL = import.meta.env.VITE_SERVER_IMAGE_API


const urlOrigin = window.location.origin
const breadCrumbPrev = 'Schools'
const breadCrumbName = route.params.name

const schoolContent = ref({})
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

const {newSchool} = storeToRefs(useSchoolsStore())
const {currentUser} = storeToRefs(useUserStore())

onBeforeMount(async () => {
    const currentSchoolName = route.params.name
    await fetchSchoolByNameAsync(currentSchoolName)
    //scenario where school is not created but Principal has entered school information in the firstVisitForm

    if (newSchool.value.schoolName &&
        Object.keys(schoolContent.value).length <= 0 &&
        (currentUser.value.role === "Principal" || currentUser.value.role === "SCHLDR")) {
        // TODO: check if newSchool.schoolName is valid inside site databasr
        triggerCreateNewSchoolFromSchoolStore().then(res => {
            console.log('fetching school after triggeringCreation')
            fetchSchoolByNameAsync(currentSchoolName)
        })
    } else {
        console.log('No new school will be created')
        showSchoolNotAvailable.value = true
    }
})


const fetchSchoolByNameAsync = (schoolName) => {
    return axios.get(`${serverURL}/fetchSchoolByName/${schoolName}`).then(res => {
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
    let processedSchoolData = {
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
        url: `${serverURL}/createSchool`,
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
        url: `${serverURL}/updateSchool`,
        method: 'post',
        data: newUpdatedSchoolFormData,
        headers: {"Content-Type": "multipart/form-data"}
    }).then(res => {
        // assign school info with newest data that has been saved succesfully to trigger update
        console.log(res.data.data)
        schoolContent.value = res.data.data
        schoolContent.value.content_blocks = parseToJsonIfString(schoolContent.value.content_blocks)
        schoolContent.value.tech_used = parseToJsonIfString(schoolContent.value.tech_used)
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


    // console.log(schoolContent.value['cover_image']);
    // if (!isCoverImageLoaded.value) {
    //     console.log('hasnt loaded');
    //     return 'https://placehold.co/600x400'
    // } else {
    //     console.log('LOADEEEEDDDD');
    //     return schoolContent.value['cover_image']
    // }
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
    return !isObjectEmpty(schoolContent.value)
})

// End of submenu specific code  plus @emit-active-tab-to-specific-page in BaseSingle

</script>
<template>
    <div v-if="isSchoolContentPopulated">
        <div class="-mt-[140px] flex flex-col ">
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
                        <template #smallTitle>
                            <!--   breadcrumb top only  -->
                            <div class=" flex mt-[100px]">
                                <div class="flex flex-row gap-2 h-[24px] place-items-center">
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
                        </template>
                        <template #titleText>
                            <div class="SchoolHeroContentContainer w-full flex flex-row">
                                <div class="w-full flex flex-row">
                                    <div class="flex flex-col">
                                        <h1 class="text-white text-[48px] font-bold">
                                            {{ schoolContent.name }}
                                        </h1>
                                        <div class="flex flex-row gap-4 place-items-center mb-4">
                                            <div
                                                v-for="(tech, index) in schoolContent.tech_used"
                                                :key="index"
                                                class="w-[60px] relative cursor-pointer"
                                            >
                                                <div
                                                    @mouseenter="handleToggleTooltip(index)"
                                                    @mouseleave="handleToggleTooltip(index)"
                                                >
                                                    <SchoolTechIconGenerator
                                                        :tech-name="tech.name"
                                                        class="min-w-[60px] pr-4 m-2 cursor-pointer relative"
                                                    />
                                                    <div
                                                        v-if="toggleTooltip && tooltipIndex === index"
                                                        class="absolute shadow-xl w-[450px] px-[24px] py-[18px] border-l-[3px] border-white bg-main-navy"
                                                    >
                                                        <h3 class="text-[24px] font-semibold text-white">
                                                            {{ tech.name }}
                                                        </h3>
                                                        <p class="text-white text-base font-normal">
                                                            {{ tech.description }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-40 h-40 flex justify-center items-center">
                                            <img
                                                :src="`${imageURL}/${schoolContent.logo}`"
                                                :alt="`${schoolContent.name} logo`"
                                                class="w-full max-h-full object-contain"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #submenu>
                            <div class="SchoolSubmenu flex flex-row gap-4 z-40 cursor-pointer">
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
                    <div class="flex flex-col w-full mt-20">
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
                                    :school-id="schoolContent['id']"
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
        class="mt-[10vh] flex flex-col justify-center items-center h-36"
    >
        <SchoolNotAvailable />
    </div>
    <div
        v-else-if="!isSchoolContentPopulated && showRetryCreateSchool"
        class="mt-[10vh] flex flex-col justify-center items-center h-36"
    >
        <GenericButton
            :callback="triggerCreateNewSchoolFromSchoolStore"
            type="school"
        >
            <div class="font-bold py-2 px-2 text-md">
                Retry create school
            </div>
        </GenericButton>
    </div>

    <div
        v-else
        class="mt-[10vh] flex flex-col justify-center items-center h-36"
    >
        <div class="font-bold text-lg">
            Please wait. Loading data...
        </div>
    </div>
</template>
