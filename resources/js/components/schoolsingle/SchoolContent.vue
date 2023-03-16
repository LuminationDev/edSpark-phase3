<script setup>
import { ref } from 'vue'
import SchoolEditorJs from "@/js/components/schoolsingle/SchoolEditorJs.vue";

const editMode = ref(false)
</script>
<template>
    <div
        class="content"
    >
        Curate your school content by adding blocks here with desired contents.
        <SchoolEditorJs />
    </div>
</template>