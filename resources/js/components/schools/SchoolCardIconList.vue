<script setup>
/**
 * Import SVG's
 */
import {computed, ref} from "vue";

import SchoolCardIcon from "@/js/components/schools/SchoolCardIcon.vue";


const props = defineProps({
    techList:{
        type: Array,
        required: false,
        default: () => []
    },
    guid:{
        type:String,
        required:false,
        default:''
    }
})

</script>

<template>
    <div
        v-for="(tech,index) in props.techList"
        :key="props.guid + '' + index"
        class="card_inner_parent"
    >
        <SchoolCardIcon
            :on-school-card="true"
            :tech-name="tech.name"
            :tech-info="tech"
            :tech-index="index"
        />
    </div>
</template>
<style>

</style>

