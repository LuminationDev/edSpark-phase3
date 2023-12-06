<script setup>
import {computed, ref, shallowRef, watchEffect} from "vue";

import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";
import SchoolsTech from "@/js/components/schools/SchoolsTech.vue";

const props = defineProps({
    techName: {
        type: String,
        required: true
    },
    techInfo: {
        type: Object,
        required: true
    },
    techIndex: {
        type: Number,
        required: false,
        default: 1
    },
    onSchoolCard: {
        type: Boolean,
        required: false,
        default: false
    },
    showFirstTech: {
        type: Boolean,
        required: false,
        default: false
    }
});


</script>
<template>
    <div
        class="group/tech my-auto schools-tech"
    >
        <SchoolTechIconGenerator
            :tech-name="props.techName"
            :on-school-card="true"
        />
    </div>
</template>
<style>

</style>