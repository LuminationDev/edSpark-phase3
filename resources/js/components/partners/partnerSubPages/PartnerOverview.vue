<script setup lang="ts">
import {storeToRefs} from "pinia";
import {onBeforeMount, onMounted, Ref, ref} from 'vue'
import {useRoute} from "vue-router";

import EditorJsControl from "@/js/components/bases/EditorJsControl.vue";
import EditorJsInput from "@/js/components/bases/EditorJsInput.vue";
import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import EditorJsContentDisplay from "@/js/components/schoolsingle/EditorJsContentDisplay.vue";
import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";
import {partnerService} from "@/js/service/partnerService";
import {useUserStore} from "@/js/stores/useUserStore";
import {EditorJSDataType} from "@/js/types/EditorJsTypes";
import {PartnerDataType} from "@/js/types/PartnerTypes";
import {SchoolDataType} from "@/js/types/SchoolTypes";

const props = defineProps({
    data: {
        type: Object as () => EditorJSDataType,
        required: false,
        default: () => {
        }
    },
    contentFromBase: {
        type: Object as () => PartnerDataType,
        required: true,
    },
    recommendationFromBase: {
        type: Object,
        required: true
    }
})


const partnerContentStateDescription = {
    new: "",
    pending_available: "You have a pending content available. Submitting new content will replace your current pending content",
    pending_loaded: "You are editing your pending content",
    submitted_pending: "You have successfully submitted content for moderation. Content will update once moderator approved your submission"
}

const buttonDescriptionByState = {
    new: "Submit content",
    pending_available: "Submit & replace pending content",
    pending_loaded: "Submit modified pending content",
    submitted_pending: "Save & replace pending content"
}
const {currentUser} = storeToRefs(useUserStore())
const route = useRoute()
const partnerId = route.params.id
const currentUserCanEdit = ref<boolean>(false)
const editMode = ref<boolean>(false)
const newPartnerContent: Ref<string> = ref(null)
const pendingPartnerProfile: Ref<any> = ref(null)
const partnerContentState = ref('new')

onBeforeMount(() => {
    newPartnerContent.value = props.contentFromBase.profile
})

onMounted(async () => {
    partnerService.checkIfUserCanEditPartner(currentUser.value.id, +partnerId).then(res => {
        // currentUserCanEdit.value = Boolean(res.data.result)
        currentUserCanEdit.value = true
        if (currentUserCanEdit.value) {
            fetchPendingContent();
        }
    })

})


const fetchPendingContent = async () => {
    try {
        const {data} = await partnerService.fetchPendingPartnerProfile(+partnerId, currentUser.value.id)
        if (data.pending_available) {
            partnerContentState.value = 'pending_available'
            pendingPartnerProfile.value = data.result.profile
        }
    } catch (e) {
        if (e.status === "404") {
        } else {
        }
    }
}
const handlePartnerDataFromEditor = (data) => {
    console.log(data)
    newPartnerContent.value = data
}

const handleEditButton = async (): Promise<void> => {
    if (pendingPartnerProfile.value) {
        await fetchPendingContent()
    }
    newPartnerContent.value = props.contentFromBase.profile
    editMode.value = true
    if (partnerContentState.value === 'pending_loaded') {
        partnerContentState.value = 'pending_available'
    }

}

// const handleAllSaveButton = async (): Promise<void> => {
//     await partnerEditorRef.value.handleEditorSave()
//     partnerService.updatePartnerContent(+partnerId, currentUser.value.id, newPartnerContent.value).then(res => {
//         if (res.status === 200) {
//             partnerContentState.value = 'submitted_pending'
//             editMode.value = false
//         } else {
//             console.error('Failed to save profile')
//         }
//     }).catch((e) => {
//         console.error(e)
//     })
// }

const handleClickEditPendingContent = (): void => {
    newPartnerContent.value = pendingPartnerProfile.value
    partnerContentState.value = 'pending_loaded'
}

</script>

<template>
    <div class="PartnerOverviewContainer flex flex-row w-full">
        <div
            class="flex flex-col partnerOverviewContent w-full lg:!basis-2/3"
        >
            <!--            <EditorJsInput-->
            <!--                ref="partnerEditorRef"-->
            <!--                :existing-data="newPartnerContent"-->
            <!--                @send-editorjs-data="handlePartnerDataFromEditor"-->
            <!--            >-->
            <!--                <template #editorjsControl>-->
            <!--                    <EditorJsControl-->
            <!--                        :load-pending-function="handleClickEditPendingContent"-->
            <!--                        :edit-function="handleEditButton"-->
            <!--                        :save-function="handleAllSaveButton"-->
            <!--                        :button-description-by-state="buttonDescriptionByState"-->
            <!--                        :editor-description-by-state="partnerContentStateDescription"-->
            <!--                        :current-state="partnerContentState"-->
            <!--                    />-->
            <!--                </template>-->
            <!--            </EditorJsInput>-->
            <template v-if="currentUserCanEdit && editMode">
                <TinyMceRichTextInput
                    :src-content="newPartnerContent"
                    :min-height="600"
                    @emit-tiny-rich-content="handlePartnerDataFromEditor"
                />
            </template>
            <template v-else>
                <div
                    class="partnerOverviewContentRenderer"
                >
                    <!--            <EditorJsContentDisplay :content-blocks="props.contentFromBase.profile" />-->
                    <div
                        class="richTextContentContainer"
                        v-html="edSparkContentSanitizer(props.contentFromBase.profile)"
                    />
                </div>
            </template>
        </div>
        <div
            v-if="currentUserCanEdit && !editMode"
            class="border-[1px] border-black flex items-center flex-col mb-2 px-4 py-4 w-full  lg:!basis-1/3"
        >
            <h2 class="font-semibold mb-2 text-genericDark text-lg">
                Admin Sections
            </h2>
            <button
                v-if="!editMode "
                class="bg-blue-600 hover:bg-blue-400 px-6 py-2 rounded text-white w-48"
                @click="handleEditButton"
            >
                Edit this page
            </button>
        </div>
    </div>
</template>
<style scoped>

:deep(p) {
    margin-top: 16px;
    text-align: justify;
}
</style>




