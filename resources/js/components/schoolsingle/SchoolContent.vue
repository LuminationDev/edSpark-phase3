<script setup>
import {computed, onBeforeMount, onMounted, ref} from 'vue'
import SchoolEditorJs from "@/js/components/schoolsingle/SchoolEditorJs.vue";
import SchoolContentDisplay from "@/js/components/schoolsingle/SchoolContentDisplay.vue";
import SchoolTech from "@/js/components/schoolsingle/SchoolTech.vue";
import TechSelector from "@/js/components/selector/TechSelector.vue";
import SchoolColorPicker from "@/js/components/schoolsingle/schoolContent/SchoolColorPicker.vue";
import SchoolImageChange from "@/js/components/schoolsingle/schoolContent/SchoolImageChange.vue";

const props = defineProps({
    schoolContent: {
        type: Object,
        required: true
    },
    colorTheme: {
        type: String, required: false
    }
})
const emits = defineEmits(['sendInfoToSchoolSingle', 'sendColorToSchoolSingle', 'sendPhotoToSchoolSingle'])

const editMode = ref(false)
const newSchoolContent = ref({})
const newTechUsed = ref([])
const schoolEditorRef = ref() // for the sake of triggering save inside editorjs component


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

const isCurrUserAdmin = computed(() => {
    console.log('inside currUserAdmin')
    console.log(props.schoolContent['id'])
    // if(props.schoolContent['id'])
    return false
})

const isCurrUserNominated = computed(() => {
    console.log('inside is nominated')
    return false
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
                    <div class="schoolAdminSection border-[1px] border-gray flex flex-col p-2 mb-2 ">
                        <h2 class="mb-2 text-slate-400">
                            Admin Sections
                        </h2>
                        <button
                            v-if="!editMode"
                            class="px-6 py-2 bg-blue-600 text-white rounded w-48"
                            @click="handleEditButton"
                        >
                            Edit This page
                        </button>
                        <slot name="additionalContentActions" />
                    </div>
                    <SchoolTech :tech-list="schoolContent.tech_used" />
                </div>
            </div>
        </div>
    </div>
</template>
