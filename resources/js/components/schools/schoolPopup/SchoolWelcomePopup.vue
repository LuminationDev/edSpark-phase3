<script setup>
import GenericPopup from "@/js/components/popup/GenericPopup.vue";
import { ref } from 'vue'

const props = defineProps({
    showPopup: {
        type: Boolean,
        required: true
    },
    closePopup:{
        type: Function, required: true
    }
})


const currentIndex = ref(0)
const schoolModalContentTeacher = [
    {
        "type": "text",
        "title" : "Welcome to school page",
        "content": "Welcome to the school page. You can browse across the pages of other schools and your own school. if you are a principal or a nominated person, you will be able to edit and customize the page. Use the browse button to browse the catalogue of schools in South Australia"
    },
    {
        "type": "text",
        "title" : "Welcome to school page",
        "content": "This page consist of two major elements which are our customizable block editors for main contents and Technology showcase column. They have been picked to allow schools to highlight their technology adaptation and creativity"
    },
    {
        "type" : 'input',
        "title" : "Contact your principal",
        "name": 'principalEmail',
        "placeholder": "insert Principal email address",
        "content": "Please insert the email address of your institution's principal to notify them about this page. (optional)",

    },
    {
        "type": "text",
        "title" : "Welcome to school page",
        "content": "If you have a request, please contact your superior officer through your designated channel of communication"
    },
    {
        "type":"text",
        "title" : "Thank you",
        "content": "Thank you going through these guides, we hope it has been helpful"
    }

]

const schoolModalContentLeader = [
    {
        "type": "text",
        "content": "Welcome to the school page. You can browse across the pages of other schools and your own school. if you are a principal or a nominated person, you will be able to edit and customize the page. Use the browse button to browse the catalogue of schools in South Australia"
    },
    {
        "type": "text",
        "content": "This page consist of two major elements which are our customizable block editors for main contents and Technology showcase column. They have been picked to allow schools to highlight their technology adaptation and creativity"
    },
    {
        "type" : 'input',
        "name": 'principalEmail',
        "placeholder": "insert Principal email address",
        "content": "Please insert the email address of your institution's principal to notify them about this page. (optional)",

    },
    {
        "type": "text",
        "content": "If you have a request, please contact your superior officer through your designated channel of communication"
    },
    {
        "type":"text",
        "content": "Thank you going through these guides, we hope it has been helpful"
    }
]

const increaseIndex = () =>{
    currentIndex.value++
}
const decreaseIndex = () =>{
    currentIndex.value--
}


</script>
<template>
    <GenericPopup
        :next-callback="increaseIndex"
        :prev-callback="decreaseIndex"
        :current-index="currentIndex"
        :modal-content="schoolModalContentLeader"
    />
</template>