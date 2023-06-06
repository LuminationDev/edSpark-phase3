<script setup>
import {computed, onBeforeMount, onMounted, ref} from 'vue'
import SchoolEditorJs from "@/js/components/schoolsingle/SchoolEditorJs.vue";
import SchoolContentDisplay from "@/js/components/schoolsingle/SchoolContentDisplay.vue";
import SchoolTech from "@/js/components/schoolsingle/SchoolTech.vue";
import TechSelector from "@/js/components/selector/TechSelector.vue";
import SchoolColorPicker from "@/js/components/schoolsingle/schoolContent/SchoolColorPicker.vue";
import SchoolImageChange from "@/js/components/schoolsingle/schoolContent/SchoolImageChange.vue";
import axios from "axios";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/js/stores/useUserStore";
import {serverURL} from "@/js/constants/serverUrl";

const props = defineProps({
    schoolContent: {
        type: Object,
        required: true
    },
    // eslint-disable-next-line vue/require-default-prop
    colorTheme: {
        type: String, required: false
    }
})
const emits = defineEmits(['sendInfoToSchoolSingle', 'sendColorToSchoolSingle', 'sendPhotoToSchoolSingle'])
const {currentUser} = storeToRefs(useUserStore())
const editMode = ref(false)
const newSchoolContent = ref({})
const newTechUsed = ref([])
const schoolEditorRef = ref() // for the sake of triggering save inside editorjs component

const currentUserCanEdit = ref(false)
const currentUserCanNominate = ref(false)

onBeforeMount(() => {
    newSchoolContent.value = props.schoolContent.content_blocks
    newTechUsed.value = props.schoolContent.tech_used
})

const handleEditButton = () => {
    editMode.value = true
}

const handleSchoolData = (data) => {
    console.log('data from schoolContent' + JSON.stringify(data))
    newSchoolContent.value = data
}

const handleSchoolTech = (techData) => {
    newTechUsed.value = techData
}

const handleAllSaveButton = () => {
    schoolEditorRef.value.handleEditorSave().then(res => {
        emits('sendInfoToSchoolSingle', newSchoolContent.value, newTechUsed.value)
        editMode.value = false

    })
}
const handleColorSelected = (newColor) => {
    emits('sendColorToSchoolSingle', newColor)
}
const handleReceivePhotoFromImageChange = (type, file) => {
    emits('sendPhotoToSchoolSingle', type, file)
}

onMounted(async () => {
    const checkIfUserCanEdit = async () => {
        await axios({
            method: "POST",
            url: `${serverURL}/checkUserCanEdit`,
            data:{
                "site_id": props.schoolContent.site.site_id,
                "user_id": currentUser.value.id,
                "school_id" : props.schoolContent.id
            }
        }).then(res => {
            console.log(res.data)
            if(res.data.status){
                if( res.data.result){
                    currentUserCanEdit.value = true
                }
                if(res.data.canNominate){
                    currentUserCanNominate.value = true
                }
            }
        })
    }
    await checkIfUserCanEdit()


})


</script>
<template>
    <div class="flex flex-col w-full">
        <div class="flex flex-row w-full">
            <div
                v-if="editMode"
                class="schoolContent contentEditor flex flex-row justify-between w-full"
            >
                <div class="flex flex-col basis-2/3">
                    Curate your school content by adding blocks here with desired contents.
                    <SchoolEditorJs
                        ref="schoolEditorRef"
                        :existing-data="newSchoolContent"
                        @send-school-data="handleSchoolData"
                    />
                </div>
                <div class="flex flex-col basis-1/3">
                    <button
                        class="px-6 py-2 bg-blue-600 text-white rounded w-48 mb-2"
                        @click="handleAllSaveButton"
                    >
                        Save Content
                    </button>
                    <SchoolImageChange @send-uploaded-photo-to-content="handleReceivePhotoFromImageChange" />
                    <SchoolColorPicker
                        class="self-center mb-5"
                        @color-selected="handleColorSelected"
                    />
                    <p class="font-semibold text-xl">
                        Tech Selector:
                    </p>
                    <TechSelector
                        :existing-tech-used="newTechUsed"
                        :color-theme="colorTheme"
                        @send-school-tech="handleSchoolTech"
                    />
                </div>
            </div>
            <div
                v-else
                class="schoolContent contentDisplay flex flex-row justify-between w-full gap-4"
            >
                <div class="basis-2/3">
                    <SchoolContentDisplay :school-content-blocks="schoolContent.content_blocks" />
                </div>
                <div class="school-tech basis-1/3">
                    <div
                        v-if="currentUserCanEdit"
                        class="schoolAdminSection border-[1px] border-black flex flex-col px-4 py-4 mb-2"
                    >
                        <h2 class="mb-2 text-genericDark font-semibold text-lg">
                            Admin Sections
                        </h2>
                        <button
                            v-if="!editMode "
                            class="px-6 py-2 bg-blue-600 text-white rounded w-48 hover:bg-blue-400"
                            @click="handleEditButton"
                        >
                            Edit this page
                        </button>
                        <slot
                            v-if="currentUserCanNominate"
                            name="additionalContentActions"
                        />
                    </div>
                    <SchoolTech :tech-list="schoolContent.tech_used" />
                </div>
            </div>
        </div>
    </div>
</template>
