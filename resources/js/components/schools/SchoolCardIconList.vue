<script setup>
/**
 * Import SVG's
 */
import SchoolCardIcon from "@/js/components/schools/SchoolCardIcon.vue";
import {computed, ref} from "vue";


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
            :tech-name="tech.name"
            :tech-info="tech"
            :tech-index="index"
            v-tippy="tech.name"
        />
    </div>
</template>
<style>

</style>

