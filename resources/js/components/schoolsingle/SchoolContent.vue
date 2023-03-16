<script setup>
import { ref } from 'vue'
import SchoolEditorJs from "@/js/components/schoolsingle/SchoolEditorJs.vue";
import SchoolContentDisplay from "@/js/components/schoolsingle/SchoolContentDisplay.vue";


const editMode = ref(false)

// Axios here call to fetch content from backenmd

const tempSchoolContent = {
    "time": 1678961115519,
    "blocks": [
        {
            "id": "WshFmTej28",
            "type": "header",
            "data": {
                "text": "About Adelaide High School"
            }
        },
        {
            "id": "72D0nar01Z",
            "type": "paragraph",
            "data": {
                "text": `Adelaide High maintains a 1:1 laptop program. Newly enrolled students are expected to purchase a device through the school. We are a M365 school and use Daymap. STEM is a strong focus and makes up one of two of the compulsory middle year subjects.`
            }
        },
        {
            "id": "L31Nwb1c39",
            "type": "header",
            "data": {
                "text": "Additional Information",
                "level": 2
            }
        },
        {
            "id": "bLn6dmXSQq",
            "type": "paragraph",
            "data": {
                "text": "Here comes another paragrah regarding the exellency of Adelaied high school with 98% high rating blabla"
            }
        },        {
            "id": "L31Nwb1c59",
            "type": "header",
            "data": {
                "text": "Our values: ",
                "level": 2
            }
        },
        {
            "id": "I_AlVcaPzy",
            "type": "list",
            "data": {
                "style": "ordered",
                "items": [
                    "We are one of the best HighSchool in Adelaide",
                    "We are proud of our teaching qualities",
                    "we put forward our practical approach to most of the curriculum",
                    "We do not compromise under no circumstances"
                ]
            }
        },
        {
            "id": "I_AlVcagery",
            "type" : "image",
            "data" : {
                "url" : "https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.jpg",
                "caption" : "Roadster // tesla.com",
                "withBorder" : false,
                "withBackground" : false,
                "stretched" : true
            }
        }
    ],
    "version": "2.26.5"
}
</script>
<template>
    <div
        v-if="editMode"
        class="schoolContent contentEditor"
    >
        Curate your school content by adding blocks here with desired contents.
        <SchoolEditorJs />
    </div>
    <div
        v-else
        class="schoolContent contentDisplay"
    >
        <SchoolContentDisplay :school-content="tempSchoolContent" />
    </div>
</template>