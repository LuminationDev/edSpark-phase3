<script setup>
/**
 * IMPORT DEPENDENCIES
 */
import {useRouter, useRoute} from 'vue-router';
import {computed, ref, watch} from 'vue'
import axios from 'axios'
import {parseToJsonIfString, schoolContentArrParser} from "@/js/helpers/jsonHelpers";
import {schoolDataFormDataBuilder, printOutFormData} from "@/js/helpers/schoolDataHelpers";
/**
 * IMPORT COMPONENTS
 */
import SchoolsProfile from '../components/schools/SchoolsProfile.vue';
import SchoolContent from "@/js/components/schoolsingle/SchoolContent.vue";
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
// import SchoolTech from "@/js/components/schoolsingle/SchoolTech.vue";
import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";

/**
 * IMPORT SVGS
 */
import SchoolsSubMenu from '../components/svg/SchoolsSubMenu.vue';
import ChevronRight from '../components/svg/ChevronRight.vue';
import {onBeforeMount} from "vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";

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

// ref to handle tooltip
const toggleTooltip = ref(false);
const tooltipIndex = ref(null);

const handleToggleTooltip = (index) => {
    toggleTooltip.value = !toggleTooltip.value;
    tooltipIndex.value = index;
}

onBeforeMount(async () => {
    // TODO Erick - Replace with get one school instead of all then filter.
    await axios.get(`${serverURL}/fetchAllSchools`).then(res => {
        const filteredSchool = res.data.filter(school => school.name === route.params.name.replace('%20', ' '))[0]
        schoolContent.value = parseToJsonIfString(filteredSchool)
        /**
         * Parse content of SchoolContent upon receiving from server.
         * avoid further processing down the components
         */
        schoolContent.value.content_blocks = parseToJsonIfString(schoolContent.value.content_blocks)
        schoolContent.value.tech_used = parseToJsonIfString(schoolContent.value.tech_used)
        schoolContent.value.cover_image = schoolContent.value.cover_image.replace("/\\/g", "")
        schoolContent.value.logo = schoolContent.value.logo.replace("/\\/g", "")
        if (filteredSchool.metadata) {
            const colorThemeMeta = schoolContent.value['metadata'].filter(meta => meta['schoolmeta_key'] === 'school_color_theme')
            colorTheme.value = colorThemeMeta[0]['schoolmeta_value']
        }
    }).catch(err => {
        console.log(err)
    });
})

const handleSaveNewSchoolInfo = async (content_blocks, tech_used) => {
    /**
     * Copy current schoolData and replace content_blocks and tech_used
     */
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
    newUpdatedSchoolFormData.append('metadata', schoolMetadata)
    await axios({
        url: `${serverURL}/updateSchool`,
        method: 'post',
        data: newUpdatedSchoolFormData,
        headers: {"Content-Type": "multipart/form-data"}
    }).then(res => {
        // assign school info with newest data that has been saved succesfully to trigger update
        console.log(res.data.data)
        schoolContent.value = res.data.data
        // parse JSON after receiving data from backend. can be done better
        // can consider computed function
        schoolContent.value.content_blocks = parseToJsonIfString(schoolContent.value.content_blocks)
        schoolContent.value.tech_used = parseToJsonIfString(schoolContent.value.tech_used)
<<<<<<< HEAD
    }).catch(err =>{
=======
    }).catch(err => {
>>>>>>> 9c4af75b41fe553df20b043847d77a2fdf76e791
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

/**
 * Cover image loading workaround
 */
const isCoverImageLoaded = ref(false)

const coverImageLink = computed(() => {
    if (!isCoverImageLoaded.value) {
        console.log('rendered placeholder image')
        return 'https://placehold.co/600x400'
    } else {
        console.log('rendered real image because finished loading')
        return `${schoolContent.value['cover_image']}`
    }
})
const handleCoverImageLoaded = () => {
    console.log('hellooo')
    isCoverImageLoaded.value = true
}

/**
 * Submenu specific codes
 */
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
    console.log('active submenu has been changed to ', value)
}
/**
 * End of submenu specific code  plus @emit-active-tab-to-specific-page in BaseSingle
 * */

</script>

<template>
    <div class="-mt-[140px] flex flex-col ">
        <img
            class="hidden"
            aria-hidden="true"
            :src="`${imageURL}/${schoolContent.cover_image}`"
            @load="handleCoverImageLoaded"
        >
        <BaseSingle
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
                                    <div class="flex flex-row gap-4 place-items-center">
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
                                                    class="absolute shadow-xl w-[450px] px-[24px] py-[18px] border-l-[3px] border-white"
                                                    :class="`bg-${colorTheme}-600`"
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
                                    <div class="w-[200px] py-6">
                                        <img
                                            :src="`${imageURL}/${schoolContent.logo}`"
                                            :alt="`${schoolContent.name} logo`"
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
                <div class="flex flex-row w-full mt-20">
                    <!--details submenu-->
                    <template v-if="activeSubmenu === schoolSubmenu[0]['value']">
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
                    </template>
                    <!--whats new submenu-->
                    <template v-if="activeSubmenu === schoolSubmenu[1]['value']">
                        <div class="text-genericDark py-2 px-10">
                            Welcome to whats new subpage
                        </div>
                    </template>
                    <!--contact submenu-->
                    <template v-if="activeSubmenu === schoolSubmenu[2]['value']">
                        <div class="text-black py-2 px-10">
                            Welcome to contact page
                        </div>
                    </template>
                </div>
            </template>
        </BaseSingle>
    </div>
</template>
