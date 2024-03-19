<script setup lang="ts">
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {storeToRefs} from "pinia";
import {computed, onBeforeMount, onMounted, Ref, ref, watch} from 'vue'
import {useRoute} from "vue-router";
import {toast} from "vue3-toastify";

import TinyMceRichTextInput from "@/js/components/bases/frontendform/TinyMceEditor/TinyMceRichTextInput.vue";
import TextInput from "@/js/components/bases/TextInput.vue";
import GenericButton from "@/js/components/button/GenericButton.vue";
import {formatDateToDayTime} from "@/js/helpers/dateHelper";
import {edSparkContentSanitizer} from "@/js/helpers/objectHelpers";
import {partnerService} from "@/js/service/partnerService";
import {useUserStore} from "@/js/stores/useUserStore";
import {EditorJSDataType} from "@/js/types/EditorJsTypes";
import {PartnerDataType} from "@/js/types/PartnerTypes";

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

enum PartnerContentState {
    New = "new",
    PendingAvailable = "pending_available",
    PendingLoaded = "pending_loaded",
}

const partnerContentStateDescription = {
    new: "",
    pending_available: "You have a pending profile awaiting for moderation from ",
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
const currentUserAdminMessage = ref('')
const tinyMceRefreshKey = ref(0)


const state = {
    introduction: '',
    motto: ''
}
const rules = {
    introduction: required,
    motto: required
}

const v$ = useVuelidate(rules, state)

onBeforeMount(() => {
    newPartnerContent.value = props.contentFromBase.profile
    v$.value.introduction.$model = props.contentFromBase.introduction
    v$.value.motto.$model = props.contentFromBase.motto
})

onMounted(async () => {
    partnerService.checkIfUserCanEditPartner(currentUser.value.id, +partnerId).then(res => {
        currentUserCanEdit.value = Boolean(res.data.result)
        currentUserAdminMessage.value = res.data.message
        console.log(res.data.result)
        if (currentUserCanEdit.value) {
            fetchPendingContent();
        }
    })

})
const forceRefreshTinyMce = () => {
    tinyMceRefreshKey.value++
}

const fetchPendingContent = async () => {
    try {
        const {data} = await partnerService.fetchPendingPartnerProfile(+partnerId, currentUser.value.id).catch(err => console.log('No pending profile'))
        if (data.pending_available) {
            partnerContentState.value = 'pending_available'
            pendingPartnerProfile.value = data.result.profile
        }
    } catch (e) {
        if (e.status === "404") {
            console.log('Not found')
        } else {
        }
    }
}
const handlePartnerDataFromEditor = (data) => {
    newPartnerContent.value = data
}

const handleEditButton = async (): Promise<void> => {
    if (partnerContentState.value === PartnerContentState.New) {
        fetchPendingContent()
    }
    newPartnerContent.value = props.contentFromBase.profile
    editMode.value = true
    if (partnerContentState.value === 'pending_loaded') {
        partnerContentState.value = 'pending_available'
    }
    forceRefreshTinyMce()

}

const handleAllSaveButton = () => {
    return partnerService.updatePartnerContent(+partnerId, currentUser.value.id, newPartnerContent.value, v$.value.introduction.$model, v$.value.motto.$model).then(res => {
        if (res.status === 200) {
            partnerContentState.value = 'submitted_pending'
            editMode.value = false
            toast(
                "You have successfully submitted content for moderation. Content will update once moderator approved your submission"
            )

        } else {
            console.error('Failed to save profile')
        }
    }).catch((e) => {
        console.error(e)
    })
}
const handleClickEditPendingContent = (): void => {
    newPartnerContent.value = pendingPartnerProfile.value
    partnerContentState.value = 'pending_loaded'
}

const handleCancelEditButton = (): void => {
    editMode.value = false
    if (pendingPartnerProfile.value) {
        partnerContentState.value = PartnerContentState.PendingAvailable
    } else {
        partnerContentState.value = PartnerContentState.New
    }

}

const userEditRole = computed(() => {
    if (currentUserAdminMessage.value.includes('Superadmin')) {
        return "Superadmin"
    } else if (currentUserAdminMessage.value.includes('partner')) {
        return 'Partner'
    }
})


const moderationStatusMessage = computed(() => {
    if (partnerContentState.value === PartnerContentState.PendingLoaded) {
        return partnerContentStateDescription[partnerContentState.value]
    } else if (pendingPartnerProfile.value) {
        return partnerContentStateDescription[partnerContentState.value] + formatDateToDayTime(pendingPartnerProfile.value.updated_at)
    } else {
        return "Your latest profile has been approved on " + formatDateToDayTime(props.contentFromBase.updated_at)
    }
})

</script>

<template>
    <div class="PartnerOverviewContainer flex flex-col w-full lg:!flex-row">
        <div
            class="flex justify-between flex-col gap-12 schoolContent w-full lg:!flex-row"
        >
            <div
                v-if="currentUserCanEdit && editMode"
                class="flex flex-col w-full lg:!basis-2/3"
            >
                <TextInput
                    v-model="v$.introduction.$model"
                    field-id="introInput"
                    :v$="v$.introduction"
                    class="my-2"
                    placeholder="Enter a short introduction of your company"
                >
                    <template #label>
                        Introduction
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.motto.$model"
                    field-id="Motto input"
                    :v$="v$.motto"
                    class="my-2"
                    placeholder="Enter your company motto. Be short and concise"
                >
                    <template #label>
                        Motto
                    </template>
                </TextInput>
                <TinyMceRichTextInput
                    :key="tinyMceRefreshKey"
                    :src-content="newPartnerContent"
                    :min-height="600"
                    @emit-tiny-rich-content="handlePartnerDataFromEditor"
                />
            </div>
            <div v-else>
                <div
                    class="partnerOverviewContentRenderer w-full lg:!basis-2/3"
                >
                    <!--            <EditorJsContentDisplay :content-blocks="props.contentFromBase.profile" />-->
                    <div
                        class="richTextContentContainer"
                        v-html="edSparkContentSanitizer(props.contentFromBase.profile)"
                    />
                </div>
            </div>
            <div
                v-if="currentUserCanEdit"
                class="flex items-center flex-col gap-4 mt-6 px-4 w-full lg:!basis-1/3 lg:!mt-0"
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
                    <template
                        v-if="editMode"
                    >
                        <div class="2xl:!grid-cols-2 grid grid-cols-1 gap-2 mb-4">
                            <GenericButton
                                v-if="partnerContentState === PartnerContentState.PendingAvailable"
                                class="bg-blue-500 hover:bg-blue-600 rounded text-base text-white w-48"
                                :callback="handleClickEditPendingContent"
                            >
                                View pending profile
                            </GenericButton>
                            <GenericButton
                                class="bg-blue-500 hover:bg-blue-600 rounded text-base text-white w-48"
                                :callback="handleAllSaveButton"
                            >
                                {{ buttonDescriptionByState[partnerContentState] }}
                            </GenericButton>
                            <GenericButton
                                class="px-6 py-2 rounded text-white w-48"
                                :callback="handleCancelEditButton"
                            >
                                Cancel edit
                            </GenericButton>
                            <GenericButton
                                v-if="partnerContentState === PartnerContentState.PendingLoaded"
                                class="!bg-secondary-mbRose mb-4 px-6 py-2 rounded-lg text-white w-48"
                                :callback="handleEditButton"
                            >
                                Revert to current
                            </GenericButton>
                        </div>
                    </template>
                    <template v-else>
                        <GenericButton
                            v-if="!editMode "
                            class="bg-blue-600 hover:bg-blue-400 mb-4 px-6 py-2 rounded text-white w-48"
                            :callback="handleEditButton"
                        >
                            Edit this page
                        </GenericButton>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
</style>




