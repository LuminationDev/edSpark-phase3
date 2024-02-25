<script setup lang="ts">
/**
 * IMPORT DEPENDENCIES
 */
import {storeToRefs} from "pinia";
import {computed, onBeforeMount, Ref, ref, watch} from 'vue'
import {useRoute} from 'vue-router';

import edSparkLogo from '@/assets/images/edsparkLogo.png'
import BaseBreadcrumb from "@/js/components/bases/BaseBreadcrumb.vue";
import BaseHero from "@/js/components/bases/BaseHero.vue";
import BaseSingle from "@/js/components/bases/BaseSingle.vue";
import BaseSingleSubmenu from "@/js/components/bases/BaseSingleSubmenu.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import SchoolDelegatePanel from "@/js/components/schools/delegatesPanel/SchoolDelegatePanel.vue";
import SchoolTechHoverableRow from "@/js/components/schools/schoolMap/SchoolTechHoverableRow.vue";
import SchoolContent from "@/js/components/schoolsingle/SchoolContent.vue";
import Loader from "@/js/components/spinner/Loader.vue";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {isObjectEmpty} from "@/js/helpers/objectHelpers";
import EdsparkPageNotFound from "@/js/pages/EdsparkPageNotFound.vue";
import {schoolService} from "@/js/service/schoolService";
import {useUserStore} from "@/js/stores/useUserStore";
import {SchoolDataType} from "@/js/types/SchoolTypes";


const route = useRoute();
const imageURL = import.meta.env.VITE_SERVER_IMAGE_API
const schoolContent: Ref<SchoolDataType | null> = ref(null)
const colorTheme = ref('teal') // default color theme
const showSchoolNotAvailable = ref(false)
const schoolNotAvailableMessage = ref('')

// ref to hold images from editing
const logoStorage = ref(null)
const coverImageStorage = ref(null)
const currentSchoolName = computed(() => {
    return route.params.name ? route.params.name : ''
})

const userStore = useUserStore()
const {currentUser} = storeToRefs(userStore)

const isPreviewMode = computed(() => {
    return route.query.preview && (userStore.getIfUserIsModerator || currentUser.value.site_id === schoolContent.value?.site?.site_id)
})

onBeforeMount(async () => {
    // will automatically create school if user is principal and school has not been created
    await schoolService.getUserSchoolByUserSiteId(currentUser.value.id, currentUser.value.site_id) // will trigger create the user's school based on the site id
    await fetchSchoolByNameAsync(currentSchoolName.value)

})

watch(currentSchoolName, async () => {
    // console.log('watcher called ')
    schoolContent.value = null
    await fetchSchoolByNameAsync(currentSchoolName.value)
})

const fetchSchoolByNameAsync = async (schoolName): Promise<void> => {
    if (isPreviewMode.value) {
        try {
            const pendingData = await schoolService.fetchPendingSchoolByName(schoolName, null, currentUser.value.id, null)
            if (pendingData) {
                schoolContent.value = pendingData
                if (schoolContent.value['metadata']) {
                    const colorThemeMeta = schoolContent.value['metadata'].filter(meta => meta['schoolmeta_key'] === 'school_color_theme');
                    if (colorThemeMeta.length > 0) {
                        colorTheme.value = colorThemeMeta[0]['schoolmeta_value'];
                    }
                }
            } else {
                showSchoolNotAvailable.value = true
                schoolNotAvailableMessage.value = "No pending content available"
                schoolContent.value = null;
            }
        } catch (err) {
            console.log(`${err.message} Inside fetchSchoolByName`);
            showSchoolNotAvailable.value = true
            schoolContent.value = null;
        }
    } else {
        try {
            schoolContent.value = await schoolService.fetchSchoolByName(schoolName)
            if (schoolContent.value['metadata']) {
                const colorThemeMeta = schoolContent.value['metadata'].filter(meta => meta['schoolmeta_key'] === 'school_color_theme');
                if (colorThemeMeta.length > 0) {
                    colorTheme.value = colorThemeMeta[0]['schoolmeta_value'];
                }
            }

        } catch (err) {
            console.log(`${err.message} Inside fetchSchoolByName`);
            showSchoolNotAvailable.value = true
            schoolContent.value = null;

        }
    }

}


const handleSaveNewSchoolInfo = async (contentBlocks, techUsed) => {
    try {
        // update school might return some stuff. 
        await schoolService.updateSchool(
            schoolContent.value, contentBlocks, techUsed, logoStorage.value, coverImageStorage.value, colorTheme.value
        );
    } catch (err) {
        console.log('Something wrong while attempting to post');
    }
}

const handleChangeColorTheme = (newColor) => {
    // console.log('received command to swap color ' + colorTheme.value + ' to -> ' + 'newColor: ' + newColor)
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

const coverImageLink = computed(() => {
    if (schoolContent.value['cover_image']) {
        return schoolContent.value['cover_image'].replace(/\\\//g, "/");
    } else {
        return;
    }
});


// Submenu specific codes
const schoolSubmenu = [
    {
        displayText: 'Details',
        value: 'detail'
    },
    // {
    //     displayText: "What's new",
    //     value: 'new'
    // },
    {
        displayText: 'Contact',
        value: 'contact'
    }
]
const activeSubmenu = ref(schoolSubmenu[0]['value'])
const handleChangeSubmenu = (value) => {
    activeSubmenu.value = value
}
// End of submenu specific code  plus @emit-active-tab-to-specific-page in BaseSingle
const isSchoolContentPopulated = computed(() => {
    return !!schoolContent.value && !isObjectEmpty(schoolContent.value)
})

// replace logo with edspark logo as a fallback image.
const handleErrorImage = (e) => {
    e.target.src = edSparkLogo
}

const handleCloseModerationTab = (): void => {
    window.close();
}
</script>
<template>
    <div v-if="isSchoolContentPopulated">
        <div class="flex flex-col">
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
                <template #hero="{ emitFromSubmenu }">
                    <BaseHero
                        :background-url="coverImageLink"
                        :swoosh-color-theme="colorTheme"
                    >
                        <template #breadcrumb>
                            <BaseBreadcrumb
                                :child-page="schoolContent.name"
                                parent-page="Schools"
                                parent-page-link="browse/school"
                                :color-theme="colorTheme"
                                class=""
                                @send-color-to-school-single="handleChangeColorTheme"
                            />
                        </template>
                        <template #titleText>
                            <div class="SchoolHeroContentContainer flex flex-row w-full">
                                <div class="flex justify-center flex-row w-full md:!justify-start">
                                    <div
                                        class="
                                            flex
                                            items-center
                                            md:items-start
                                            flex-row
                                            pl-8
                                            lg:pl-0
                                            lg:pl-0
                                            gap-4
                                            sm:mx-0
                                            ">
                                        <div
                                            class="
                                                bg-white
                                                flex
                                                justify-center
                                                items-center
                                                h-full
                                                md:h-[200px]
                                                rounded-md
                                                text-md
                                                md:w-[200px]
                                                "
                                        >
                                            <img
                                                :src="`${imageURL}/${schoolContent.logo}`"
                                                :alt="`school logo`"
                                                class="
                                                    bg-white
                                                    h-52
                                                    max-h-full
                                                    object-contain
                                                    p-3
                                                    rounded
                                                    w-52
                                                    "
                                                @error="handleErrorImage"
                                            >
                                        </div>

                                        <div class="flex flex-col hidden pl-8 lg:pl-0 md:block">
                                            <h1 class="font-bold pb-4 text-white">
                                                {{ schoolContent.name }}
                                            </h1>
                                            <div
                                                class="
                                                    flex
                                                    flex-row
                                                    flex-wrap
                                                    place-items-center
                                                    schoolTechHoverableRow
                                                    gap-1
                                                    ">
                                                <SchoolTechHoverableRow
                                                    :tech-used-list="schoolContent.tech_used"
                                                    :color-theme="colorTheme"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template #submenu>
                            <div class="SchoolSubmenu cursor-pointer flex flex-row gap-2 mb-[-1px] z-40 md:!gap-4">
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
                    <div class="flex flex-col w-full">
                        <div
                            v-if="isPreviewMode && schoolContent.name"
                            class="flex justify-center flex-row"
                        >
                            <div class="basis-4/5 font-semibold mb-4 previewLabel text-center text-xl">
                                Preview content (Moderation)
                                <div class="font-medium text-base text-center">
                                    Created at: {{
                                        schoolContent['updated_at'] ? formatDateToDayTime(schoolContent['updated_at']) : ''
                                    }}
                                    by <span class="font-semibold"> {{ schoolContent?.owner?.owner_name || "" }} </span>
                                </div>
                            </div>
                            <div class="basis-1/5 flex p-2">
                                <GenericButton :callback="handleCloseModerationTab">
                                    Back to moderation
                                </GenericButton>
                            </div>
                        </div>
                        <SchoolContent
                            :school-content="schoolContent"
                            :color-theme="colorTheme"
                            :active-submenu="activeSubmenu"
                            :is-preview-mode="isPreviewMode"
                            @send-info-to-school-single="handleSaveNewSchoolInfo"
                            @send-color-to-school-single="handleChangeColorTheme"
                            @send-photo-to-school-single="handleReceivePhotoFromContent"
                        >
                            <template #additionalContentActions>
                                <div class="DelegationPanelOuterContainer flex flex-col mt-4 w-full">
                                    <SchoolDelegatePanel
                                        :school-id="schoolContent?.school_id"
                                        :site-id="schoolContent?.site?.site_id"
                                    />
                                </div>
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
        <EdsparkPageNotFound
            :error-message="schoolNotAvailableMessage ? schoolNotAvailableMessage : 'School not available. Please check again later'"
        />
    </div>

    <div
        v-else
        class="font-semibold mt-20 text-xl"
    >
        <Loader
            :loader-color="'#0072DA'"
            :loader-message="'School loading'"
        />
    </div>
</template>


