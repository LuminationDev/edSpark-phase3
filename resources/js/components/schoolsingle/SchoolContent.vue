<script setup>
import {reactive, ref, onMounted} from 'vue'
import SchoolEditorJs from "@/js/components/schoolsingle/SchoolEditorJs.vue";
import SchoolContentDisplay from "@/js/components/schoolsingle/SchoolContentDisplay.vue";

const props = defineProps({
    schoolContent: {
        type: Object,
        required : true
    }
})
const emits = defineEmits(['sendInfoToParent'])

const editMode = ref(false)

const handleEditButton = () => {
    editMode.value = true
}

const handleSaveButton = (data) =>{
    console.log('data from schoolContent' + JSON.stringify(data))
    emits('sendInfoToParent', data)
    editMode.value = false

}
// update: no state here and leave the entirety of logic to parent

</script>
<template>
    <div
        v-if="editMode"
        class="schoolContent contentEditor"
    >
        Curate your school content by adding blocks here with desired contents.
        <SchoolEditorJs
            :existing-data="schoolContent.content_blocks"
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