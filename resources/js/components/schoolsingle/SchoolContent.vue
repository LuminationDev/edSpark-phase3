<script setup>
import {ref, onMounted} from 'vue'
import SchoolEditorJs from "@/js/components/schoolsingle/SchoolEditorJs.vue";
import SchoolContentDisplay from "@/js/components/schoolsingle/SchoolContentDisplay.vue";

const props = defineProps({
    schoolContent: {
        type: Object,
        required : true
    }
})

const editMode = ref(false)

const handleEditButton = () => {
    editMode.value = true
}

const handleSaveButton = (data) =>{
    console.log('data from schoolContent' + JSON.stringify(data))
    tempSchoolContent.value = data
    editMode.value = false

}

</script>
<template>
    <div
        v-if="editMode"
        class="schoolContent contentEditor"
    >
        Curate your school content by adding blocks here with desired contents.
        <SchoolEditorJs
            :existing-data="schoolContent"
            @save-new-data="handleSaveButton"
        />
    </div>
    <div
        v-else
        class="schoolContent contentDisplay"
    >
        <SchoolContentDisplay :school-content-blocks="schoolContent.content_blocks" />
    </div>
    <button
        v-if="!editMode"
        class="px-6 py-2 bg-blue-600 text-white rounded"
        @click="handleEditButton"
    >
        Edit This page
    </button>
</template>