<script setup>
import {ref} from 'vue'
import SchoolEditorJs from "@/js/components/schoolsingle/SchoolEditorJs.vue";
import SchoolContentDisplay from "@/js/components/schoolsingle/SchoolContentDisplay.vue";


const editMode = ref(false)

// Axios here call to fetch content from backenmd

let tempSchoolContent = ref({
    "time": 1678961115519,
    "blocks": [
        {
            "id": "bFYQTCRyxO",
            "type": "header",
            "data": {"text": "<b><font size=\"6\">About Adelaide High School</font></b>", "level": 2}
        }, {
            "id": "Ut01dKhFzb",
            "type": "paragraph",
            "data": {"text": "Adelaide High maintains a 1:1 laptop program. Newly enrolled students are expected to purchase a device through the school. We are a M365 school and use Daymap. STEM is a strong focus and makes up one of two of the compulsory middle year subjects."}
        }, {
            "id": "inGB8KadHC",
            "type": "header",
            "data": {"text": "<a href=\"http://www.google.com\">Additional Information</a>", "level": 2}
        }, {
            "id": "Jxk9pSC9vS",
            "type": "paragraph",
            "data": {"text": "<font size=\"5\">Here comes another paragrah regarding the exellency of Adelaied high school with 98% high rating blabla</font>"}
        }, {
            "id": "M7o2TjPh0d",
            "type": "header",
            "data": {"text": "<i>Our values: </i>", "level": 2}
        }, {
            "id": "dA4Jagclhf",
            "type": "list",
            "data": {
                "style": "unordered",
                "items": ["We are one of the best HighSchool in Adelaide", "<font size=\"7\">We are proud of our teaching qualities</font>", "we put forward our practical approach to most of the curriculum", "We do not compromise under no circumstances"]
            }
        },
        {
            "id": "I_AlVcagery",
            "type": "image",
            "data": {
                "url": "https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.jpg",
                "caption": "Roadster // tesla.com",
                "withBorder": false,
                "withBackground": false,
                "stretched": true
            }
        }
    ],
    "version": "2.26.5"
})
// console.log(JSON.stringify(tempSchoolContent))

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
            :existing-data="tempSchoolContent"
            @save-new-data="handleSaveButton"
        />
    </div>
    <div
        v-else
        class="schoolContent contentDisplay"
    >
        <SchoolContentDisplay :school-content="tempSchoolContent" />
    </div>
    <button
        v-if="!editMode"
        class="px-6 py-2 bg-blue-600 text-white rounded"
        @click="handleEditButton"
    >
        Edit This page
    </button>
</template>