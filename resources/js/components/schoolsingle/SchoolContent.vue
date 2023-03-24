<script setup>
import {onBeforeMount, onMounted, ref} from 'vue'
import SchoolEditorJs from "@/js/components/schoolsingle/SchoolEditorJs.vue";
import SchoolContentDisplay from "@/js/components/schoolsingle/SchoolContentDisplay.vue";
import SchoolTech from "@/js/components/schoolsingle/SchoolTech.vue";
import TechSelector from "@/js/components/selector/TechSelector.vue";

const props = defineProps({
    schoolContent: {
        type: Object,
        required : true
    }
})
const emits = defineEmits(['sendInfoToParent'])

const editMode = ref(true)
const newSchoolContent = ref({})
const newTechUsed = ref([])

onBeforeMount(() => {
    newSchoolContent.value = props.schoolContent.content_blocks
    newTechUsed.value = props.schoolContent.tech_used
})
const handleEditButton = () => {
    editMode.value = true
}

const handleSchoolData = (data) =>{
    console.log('data from schoolContent' + JSON.stringify(data))
    // emits('sendInfoToParent', data)
    newSchoolContent.value =  data
}

const handleSchoolTech = (techData) => {
    newTechUsed.value = techData

}
const schoolEditorRef = ref()
const handleAllSaveButton = async () => {
    await schoolEditorRef.value.handleEditorSave() //  will update newSchoolContent Automatically
    emits('sendInfoToParent', newSchoolContent.value, newTechUsed.value)
    editMode.value = false
}

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
                    Tech selector component to go here
                    <TechSelector
                        :existing-tech-used="newTechUsed"
                        @send-school-tech="handleSchoolTech"
                    />
                </div>
            </div>
            <div
                v-else
                class="schoolContent contentDisplay flex flex-row justify-between w-full"
            >
                <div class="basis-2/3">
                    <SchoolContentDisplay :school-content-blocks="schoolContent.content_blocks" />
                </div>
                <div class="school-tech basis-1/3">
                    <SchoolTech :tech-list="schoolContent.tech_used" />
                </div>
            </div>
        </div>

        <button
            v-if="!editMode"
            class="px-6 py-2 bg-blue-600 text-white rounded w-48"
            @click="handleEditButton"
        >
            Edit This page
        </button>
        <button
            v-else
            class="w-36 rounded-lg px-2 py-4  bg-slate-500"
            @click="handleAllSaveButton"
        >
            Save Content
        </button>
    </div>
</template>