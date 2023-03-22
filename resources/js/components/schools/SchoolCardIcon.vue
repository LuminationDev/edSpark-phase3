<script setup>
import {shallowRef, watchEffect} from "vue";
import SchoolsTech from "@/js/components/schools/SchoolsTech.vue";
import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";
const props = defineProps({
    techName:{
        type: String,
        required: true
    },
    techInfo:{
        type: Object,
        required: true
    }
});


console.log(props);
</script>
<template>
    <div class="my-auto group/tech schools-tech">
        <SchoolTechIconGenerator :tech-name="props.techName" />
        <SchoolsTech :tech-hover="techInfo" />
    </div>
</template>
