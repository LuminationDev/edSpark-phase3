<script setup lang="ts">
import {storeToRefs} from "pinia";
import {computed, onBeforeMount,  onMounted, Ref, ref} from 'vue'
import {onBeforeRouteLeave, useRoute} from "vue-router";
import {toast} from "vue3-toastify";

import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import SchoolContact from "@/js/components/schoolsingle/SchoolContact.vue";
import SchoolColorPicker from "@/js/components/schoolsingle/schoolContent/SchoolColorPicker.vue";
import SchoolImageChange from "@/js/components/schoolsingle/schoolContent/SchoolImageChange.vue";
import SchoolHowToUseTech from "@/js/components/schoolsingle/schoolhowtousetech/SchoolHowToUseTech.vue";
import SchoolHowToUseTechRenderer from "@/js/components/schoolsingle/schoolhowtousetech/SchoolHowToUseTechRenderer.vue";
import SchoolTech from "@/js/components/schoolsingle/SchoolTech.vue";
import SchoolWhatsNew from "@/js/components/schoolsingle/SchoolWhatsNew.vue";
import TechSelector from "@/js/components/selector/TechSelector.vue";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";
import {schoolService} from "@/js/service/schoolService";
import {useUserStore} from "@/js/stores/useUserStore";
import {SchoolDataType, TechUsed} from "@/js/types/SchoolTypes";


const schoolContentStateDescription = {
    new: "",
    pending_available: "You have a pending profile awaiting for moderation from ",
    pending_loaded: "You are editing your pending profile",
}
const buttonDescriptionByState = {
    new: "Submit for moderation",
    pending_available: "Submit for moderation",
    pending_loaded: "Submit for moderation",
}

enum SchoolContentState {
    New = "new",
    PendingAvailable = "pending_available",
    PendingLoaded = "pending_loaded",
}

const props = defineProps({
    schoolContent: {
        type: Object as () => SchoolDataType,
        required: true
    },
    colorTheme: {
        type: String,
        required: false,
        default: 'darkTeal',
    },
    activeSubmenu: {
        type: String,
        required: true
    },
    isPreviewMode: {
        type: Boolean,
        required: false,
        default: false
    }
})
const route = useRoute()

const emits = defineEmits(['sendInfoToSchoolSingle', 'sendColorToSchoolSingle', 'sendPhotoToSchoolSingle', 'resetColorTheme'])
const {currentUser} = storeToRefs(useUserStore())
const currentSchoolName = route.params.name
const editMode = ref<boolean>(false)
const newSchoolContent: Ref<string> = ref("")
const pendingSchoolContent: Ref<SchoolDataType | null> = ref(null)
const schoolContentState = ref(SchoolContentState.New)
const newTechUsed: Ref<TechUsed[] | null> = ref(null)
const newTechLandscape = ref(null)

const currentUserCanEdit = ref<boolean>(false)
const currentUserCanNominate = ref<boolean>(false)
const currentUserCanPublish = ref<boolean>(false)
const currentUserAdminMessage = ref<string>('')
const tinyMceRefreshKey = ref(0)

const requiresConfirmationBeforeExit = ref(false)

const setConfirmToTrue = () => {
    if (requiresConfirmationBeforeExit.value) return;
    requiresConfirmationBeforeExit.value = true
}

const forceRefreshTinyMce = () => {
    tinyMceRefreshKey.value++
}

onBeforeMount(() => {
    newSchoolContent.value = props.schoolContent.content_blocks
    newTechUsed.value = _.cloneDeep(props.schoolContent.tech_used)
    newTechLandscape.value = props.schoolContent.tech_landscape
})

const handleEditButton = async () => {
    if (schoolContentState.value === 'new') {
        checkIfPendingAvailable()
    }
    newSchoolContent.value = props.schoolContent.content_blocks
    newTechUsed.value = _.cloneDeep(props.schoolContent.tech_used)
    newTechLandscape.value = props.schoolContent.tech_landscape
    editMode.value = true
    // pressing the revert button
    if (schoolContentState.value === 'pending_loaded') {
        schoolContentState.value = SchoolContentState.PendingAvailable
    }
    forceRefreshTinyMce()
}

const handleCancelEditButton = (): void => {
    emits('resetColorTheme');
    editMode.value = false
    if (pendingSchoolContent.value) {
        schoolContentState.value = SchoolContentState.PendingAvailable
    } else {
        schoolContentState.value = SchoolContentState.New
    }
}

const handleSchoolData = (data): void => {
    setConfirmToTrue()
    newSchoolContent.value = data
}

const handleSchoolTech = (techData): void => {
    setConfirmToTrue()
    newTechUsed.value = techData
}

const handleReceiveTechLandscape = (data) => {
    newTechLandscape.value = data
}

const handleAllSaveButton = (): void => {
    emits('sendInfoToSchoolSingle', newSchoolContent.value, newTechUsed.value, newTechLandscape.value)
    editMode.value = false
    schoolContentState.value = SchoolContentState.New
    // reset color here
    emits('resetColorTheme');
    toast('Submitted your new profile for moderation. View will update automatically once approved')
    checkIfPendingAvailable()

}

const handleColorSelected = (newColor): void => {
    setConfirmToTrue()
    emits('sendColorToSchoolSingle', newColor)
}

const handleReceivePhotoFromImageChange = (type, file): void => {
    setConfirmToTrue()
    emits('sendPhotoToSchoolSingle', type, file)
}

onMounted(async () => {
    if (!props.isPreviewMode) {
        await schoolService.checkIfUserCanEdit(props.schoolContent.site.site_id, props.schoolContent.school_id).then(res => {
            currentUserCanEdit.value = Boolean(res.data.status && res.data.result)
            currentUserCanNominate.value = Boolean(res.data.status && res.data.canNominate)
            currentUserCanPublish.value = Boolean(res.data.status && res.data.canPublish)
            currentUserAdminMessage.value = res.data.message

        })
        if (currentUserCanEdit.value) {
            await checkIfPendingAvailable()
        }

    }
})

const checkIfPendingAvailable = async (): Promise<void> => {
    const result = await schoolService.fetchPendingSchoolByName(currentSchoolName, props.schoolContent.site.site_id, currentUser.value.id, props.schoolContent.school_id)
    if (result) {
        schoolContentState.value = SchoolContentState.PendingAvailable
        pendingSchoolContent.value = result
    }
}

const handleClickEditPendingContent = (): void => {
    newSchoolContent.value = pendingSchoolContent.value.content_blocks
    newTechUsed.value = pendingSchoolContent.value.tech_used
    newTechLandscape.value = pendingSchoolContent.value.tech_landscape || ""
    schoolContentState.value = SchoolContentState.PendingLoaded
    forceRefreshTinyMce()
}

const userEditRole = computed(() => {
    if (currentUserAdminMessage.value.includes('Superadmin')) {
        return "Superadmin"
    } else if (currentUserAdminMessage.value.includes('leader')) {
        return 'School leader'
    } else if (currentUserAdminMessage.value.includes('nominated user')) {
        return 'Nominated user'
    }
})

const moderationStatusMessage = computed(() => {
    if (schoolContentState.value === SchoolContentState.PendingLoaded) {
        return schoolContentStateDescription[schoolContentState.value]
    } else if (pendingSchoolContent.value) {
        return schoolContentStateDescription[schoolContentState.value] + formatDateToDayTime(pendingSchoolContent.value.updated_at)
    } else {
        return "Your latest profile has been approved on " + formatDateToDayTime(props.schoolContent.updated_at)
    }
})



onBeforeRouteLeave(() =>{
    if(requiresConfirmationBeforeExit.value){
        if (!confirm("You have unsaved changes. Are you sure you want to leave?")) {
            // Prevent route from leaving
            return false;
        }
    }
})
</script>
<template>
    <div class="flex flex-col w-full">
        <template v-if="props.activeSubmenu === 'detail'">
            <div
                v-if="Object.keys(schoolContent).length > 1"
                class="flex flex-col px-5 py-2 school-content w-full lg:!px-10"
            >
                <div class="flex flex-row w-full">
                    <div
                        class="flex justify-between flex-col gap-12 schoolContent w-full lg:!flex-row"
                    >
                        <!--        Beginning of Edit mode                -->
                        <div
                            v-if="editMode"
                            class="flex flex-col w-full lg:!basis-2/3"
                        >
                            <div class="border-b-2 border-black mb-4 mt-2 text-2xl w-full">
                                School Profile
                            </div>
                            <TinyMceRichTextInput
                                :key="tinyMceRefreshKey"
                                :src-content="newSchoolContent"
                                :min-height="1000"
                                @emit-tiny-rich-content="handleSchoolData"
                            />
                        </div>
                        <div
                            v-else
                            class="w-full lg:!basis-2/3"
                        >
                            <div
                                class="richTextContentContainer"
                                v-html="edSparkContentSanitizer(schoolContent.content_blocks)"
                            />
                        </div>

                        <div
                            class="flex items-center flex-col gap-4 px-4 w-full lg:!basis-1/3"
                        >
                            <h2
                                v-if="currentUserCanEdit"
                                class="font-semibold text-center text-genericDark text-lg w-full"
                            >
                                Admin Sections
                            </h2>
                            <div
                                v-if="currentUserCanEdit"
                                class="
                                    border-[1px]
                                    border-gray-300
                                    flex
                                    items-center
                                    flex-col
                                    mb-2
                                    pb-4
                                    pt-2
                                    px-4
                                    rounded
                                    schoolAdminSection
                                    w-full
                                    "
                            >
                                <p>
                                    Your Role
                                </p>
                                <p class="font-semibold mb-4">
                                    {{ userEditRole }}
                                </p>
                                <p>
                                    Moderation status
                                </p>
                                <p class="font-semibold mb-4 text-center">
                                    {{ moderationStatusMessage }}
                                </p>
                                <!--      RHS edit mode     -->
                                <template
                                    v-if="editMode"
                                >
                                    <div class="2xl:!grid-cols-2 grid grid-cols-1 gap-2 mb-4">
                                        <GenericButton
                                            v-if="schoolContentState === SchoolContentState.PendingAvailable"
                                            class="
                                                bg-blue-500
                                                hover:bg-blue-600
                                                rounded
                                                text-base
                                                text-white
                                                w-48
                                                "
                                            :callback="handleClickEditPendingContent"
                                        >
                                            View pending profile
                                        </GenericButton>
                                        <GenericButton
                                            class="
                                                bg-blue-500
                                                hover:bg-blue-600
                                                rounded
                                                text-base
                                                text-white
                                                w-48
                                                "
                                            :callback="handleAllSaveButton"
                                        >
                                            {{ buttonDescriptionByState[schoolContentState] }}
                                        </GenericButton>
                                        <GenericButton
                                            class="px-6 py-2 rounded text-white w-48"
                                            :callback="handleCancelEditButton"
                                        >
                                            Cancel edit
                                        </GenericButton>
                                        <GenericButton
                                            v-if="schoolContentState === SchoolContentState.PendingLoaded"
                                            class="
                                                !bg-secondary-mbRose
                                                mb-4
                                                px-6
                                                py-2
                                                rounded-lg
                                                text-white
                                                w-48
                                                "
                                            :callback="handleEditButton"
                                        >
                                            Revert to current
                                        </GenericButton>
                                    </div>

                                    <SchoolColorPicker
                                        class="mb-6"
                                        @color-selected="handleColorSelected"
                                    />
                                    <SchoolImageChange
                                        class="mb-6"
                                        :current-logo="props.schoolContent.logo"
                                        :current-cover-image="props.schoolContent.cover_image"
                                        @send-uploaded-photo-to-content="handleReceivePhotoFromImageChange"
                                    />
                                </template>
                                <template v-else>
                                    <GenericButton
                                        v-if="!editMode"
                                        class="
                                            bg-blue-600
                                            hover:bg-blue-400
                                            mb-4
                                            px-6
                                            py-2
                                            rounded
                                            text-white
                                            w-48
                                            "
                                        :callback="handleEditButton"
                                    >
                                        Edit this page
                                    </GenericButton>
                                    <slot
                                        v-if="currentUserCanNominate"
                                        name="additionalContentActions"
                                    />
                                </template>
                            </div>

                            <div
                                class="contentDisplay flex items-center flex-col schoolContent w-full"
                            >
                                <SchoolTech
                                    v-if="!editMode"
                                    :tech-list="schoolContent.tech_used"
                                    :color-theme="colorTheme"
                                />
                            </div>
                        </div>
                    </div>
                    <!--    Display Content     -->
                </div>
                <div
                    v-if="editMode"
                    class="flex flex-col w-full"
                >
                    <div class="border-b-2 border-black mb-4 mt-6 text-2xl w-full">
                        How to use tech
                    </div>
                    <div class="mb-4">
                        Select technologies being used at your schools from the selector and provide more information in the text box with few related images. This information will be showed on the "How to use tech" tab.
                    </div>
                    <div class="flex flex-col px-4">
                        <div class="flex flex-col lg:!flex-row">
                            <div class="w-full lg:!w-1/4">
                                <TechSelector
                                    :existing-tech-used="newTechUsed"
                                    :color-theme="colorTheme"
                                    @send-school-tech="handleSchoolTech"
                                />
                            </div>
                            <div class="w-full lg:!w-3/4">
                                <SchoolHowToUseTech
                                    :tech-used="newTechUsed"
                                    :tech-landscape="props.schoolContent.tech_landscape"
                                    @emit-tech-landscape="handleReceiveTechLandscape"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-if="props.activeSubmenu === 'new'">
            <SchoolWhatsNew />
        </template>
        <template v-if="props.activeSubmenu === 'contact'">
            <SchoolContact
                :current-user-can-edit="currentUserCanEdit"
                :school-id="props.schoolContent['school_id']"
                :school-location="props.schoolContent['location']"
            />
        </template>
        <template v-if="props.activeSubmenu === 'how-to-use-tech'">
            <SchoolHowToUseTechRenderer
                :tech-landscape="props.schoolContent.tech_landscape"
                :tech-used="props.schoolContent.tech_used"
            />
        </template>
    </div>
</template>
