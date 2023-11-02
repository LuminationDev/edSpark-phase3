<script setup>
import {computed, ref, shallowRef, watchEffect} from "vue";
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
    },
    techIndex:{
        type: Number,
        required: false,
        default:1
    },
    showFirstTech:{
        type: Boolean,
        required: false,
        default: false
    }
});



</script>
<template>
    <div
        class="my-auto group/tech schools-tech" >
        <SchoolTechIconGenerator :tech-name="props.techName" style="width: 44px; height: 44px;"/>
        <!-- <SchoolsTech
            :tech-hover="techInfo"
            :tech-index="props.techIndex"
            :show-first-tech="showFirstTech"
        /> -->
    </div>
</template>
<style>

</style>