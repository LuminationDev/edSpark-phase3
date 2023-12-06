<script setup lang="ts">
import {storeToRefs} from "pinia";
import {onBeforeMount, onMounted, Ref, ref} from 'vue'
import {useRoute} from "vue-router";
import {toast} from "vue3-toastify";

import EditorJsInput from "@/js/components/bases/EditorJsInput.vue";
import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import EditorJsContentDisplay from "@/js/components/schoolsingle/EditorJsContentDisplay.vue";
import SchoolContact from "@/js/components/schoolsingle/SchoolContact.vue";
import SchoolColorPicker from "@/js/components/schoolsingle/schoolContent/SchoolColorPicker.vue";
import SchoolImageChange from "@/js/components/schoolsingle/schoolContent/SchoolImageChange.vue";
import SchoolTech from "@/js/components/schoolsingle/SchoolTech.vue";
import SchoolWhatsNew from "@/js/components/schoolsingle/SchoolWhatsNew.vue";
import TechSelector from "@/js/components/selector/TechSelector.vue";
import {defaultSchoolContent} from "@/js/constants/schoolContentDefault";
import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";
import {schoolService} from "@/js/service/schoolService";
import {useUserStore} from "@/js/stores/useUserStore";
import {EditorJSDataType} from "@/js/types/EditorJsTypes";
import {SchoolDataType, TechUsed} from "@/js/types/SchoolTypes";


const schoolContentStateDescription = {
    new: "",
    pending_available: "You have a pending content available. Submitting new content will replace your current pending content",
    pending_loaded: "You are editing your pending content",
}
const buttonDescriptionByState = {
    new: "Submit content",
    pending_available: "Submit & replace pending content",
    pending_loaded: "Submit modified pending content",
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
        default: 'teal',
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


const emits = defineEmits(['sendInfoToSchoolSingle', 'sendColorToSchoolSingle', 'sendPhotoToSchoolSingle'])
const {currentUser} = storeToRefs(useUserStore())
const currentSchoolName = route.params.name
const editMode = ref<boolean>(false)
const newSchoolContent: Ref<string> = ref("")
const pendingSchoolContent: Ref<SchoolDataType | null> = ref(null)
const schoolContentState = ref(SchoolContentState.New)
const newTechUsed: Ref<TechUsed[] | null> = ref(null)

const currentUserCanEdit = ref<boolean>(false)
const currentUserCanNominate = ref<boolean>(false)

onBeforeMount(() => {
    newSchoolContent.value = props.schoolContent.content_blocks
    newTechUsed.value = _.cloneDeep(props.schoolContent.tech_used)
})

const handleEditButton = async () => {
    if (schoolContentState.value === 'new') {
        checkIfPendingAvailable()
    }
    newSchoolContent.value = props.schoolContent.content_blocks
    newTechUsed.value = _.cloneDeep(props.schoolContent.tech_used)
    editMode.value = true
    // pressing the revert button
    if (schoolContentState.value === 'pending_loaded') {
        schoolContentState.value = SchoolContentState.PendingAvailable
    }
}

const handleCancelEditButton = (): void => {
    editMode.value = false
}

const handleSchoolData = (data): void => {
    newSchoolContent.value = data
}

const handleSchoolTech = (techData): void => {
    newTechUsed.value = techData
}

const handleAllSaveButton = (): void => {
    emits('sendInfoToSchoolSingle', newSchoolContent.value, newTechUsed.value)
    editMode.value = false
    schoolContentState.value = SchoolContentState.New
    toast('Submitted your new profile for moderation. View will update automatically once approved')
}

const handleColorSelected = (newColor): void => {
    emits('sendColorToSchoolSingle', newColor)
}

const handleReceivePhotoFromImageChange = (type, file): void => {
    emits('sendPhotoToSchoolSingle', type, file)
}

onMounted(async () => {
    if (!props.isPreviewMode) {
        await schoolService.checkIfUserCanEdit(props.schoolContent.site.site_id, currentUser.value.id, props.schoolContent.school_id).then(res => {
            console.log(res)
            currentUserCanEdit.value = Boolean(res.data.status && res.data.result)
            currentUserCanNominate.value = Boolean(res.data.status && res.data.canNominate)

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
    schoolContentState.value = SchoolContentState.PendingLoaded
}


console.log(props.activeSubmenu)
</script>
<template>
    <div class="flex flex-col w-full">
        <template v-if="props.activeSubmenu === 'detail'">
            <div
                v-if="Object.keys(schoolContent).length > 1"
                class="flex px-5 py-2 school-content w-full lg:!px-10"
            >
                <div class="flex flex-row w-full">
                    <div
                        v-if="editMode"
                        class="contentEditor flex justify-between flex-col schoolContent w-full lg:!flex-row"
                    >
                        <div class="flex flex-col w-full lg:!basis-2/3">
                            Curate your school content by adding blocks here with desired contents.
                            <!--                            <EditorJsInput-->
                            <!--                                ref="schoolEditorRef"-->
                            <!--                                :existing-data="newSchoolContent"-->
                            <!--                                @send-editorjs-data="handleSchoolData"-->
                            <!--                            />-->
                            <TinyMceRichTextInput
                                :src-content="newSchoolContent"
                                :min-height="600"
                                @emit-tiny-rich-content="handleSchoolData"
                            />
                        </div>
                        <div class="flex items-center flex-col w-full lg:!basis-1/3">
                            <div
                                class="flex font-semibold mb-4 text-center"
                            >
                                {{ schoolContentStateDescription[schoolContentState] }}
                            </div>
                            <div class="mb-10">
                                <GenericButton
                                    v-if="schoolContentState === SchoolContentState.PendingAvailable"
                                    class="bg-blue-500 hover:bg-blue-600 mb-2 px-6 py-2 rounded text-white w-48"
                                    :callback="handleClickEditPendingContent"
                                >
                                    Edit pending content
                                </GenericButton>
                                <GenericButton
                                    class="bg-blue-500 hover:bg-blue-600 mb-2 px-6 py-2 rounded text-white w-48"
                                    :callback="handleAllSaveButton"
                                >
                                    {{ buttonDescriptionByState[schoolContentState] }}
                                </GenericButton>
                                <GenericButton
                                    class="!bg-secondary-mbRose mb-4 px-6 py-2 rounded text-white w-48"
                                    :callback="handleCancelEditButton"
                                >
                                    Cancel edit
                                </GenericButton>
                            </div>
                            <button
                                v-if="schoolContentState === SchoolContentState.PendingLoaded"
                                class="bg-blue-500 hover:bg-blue-600 mb-4 px-6 py-2 rounded text-white w-48"
                                @click="handleEditButton"
                            >
                                Revert
                            </button>
                            <SchoolImageChange
                                :current-logo="props.schoolContent.logo"
                                :current-cover-image="props.schoolContent.cover_image"
                                @send-uploaded-photo-to-content="handleReceivePhotoFromImageChange"
                            />
                            <SchoolColorPicker
                                class="my-10 self-center"
                                @color-selected="handleColorSelected"
                            />
                            <p class="font-semibold mb-5 mt-10 text-xl">
                                Tech Selector:
                            </p>
                            <TechSelector
                                :existing-tech-used="newTechUsed"
                                :color-theme="colorTheme"
                                @send-school-tech="handleSchoolTech"
                            />
                        </div>
                    </div>
                    <!--    Display Content     -->
                    <div
                        v-else
                        class="contentDisplay flex lg:flex-row justify-between flex-col gap-4 schoolContent w-full"
                    >
                        <div class="basis-2/3">
                            <div
                                class="richTextContentContainer"
                                v-html="edSparkContentSanitizer(schoolContent.content_blocks)"
                            />
                        </div>
                        <div class="basis-1/3 school-tech">
                            <div
                                v-if="currentUserCanEdit"
                                class="border-[1px] border-black flex flex-col mb-2 px-4 py-4 schoolAdminSection"
                            >
                                <h2 class="font-semibold mb-2 text-genericDark text-lg">
                                    Admin Sections
                                </h2>
                                <GenericButton
                                    v-if="!editMode "
                                    class="bg-blue-600 hover:bg-blue-400 px-6 py-2 rounded text-white w-48"
                                    :callback="handleEditButton"
                                >
                                    Edit this page
                                </GenericButton>
                                <slot
                                    v-if="currentUserCanNominate"
                                    name="additionalContentActions"
                                />
                            </div>
                            <SchoolTech
                                :tech-list="schoolContent.tech_used"
                                :color-theme="colorTheme"
                            />
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
    </div>
</template>
