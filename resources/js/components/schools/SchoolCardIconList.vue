<script setup>
/**
 * Import SVG's
 */
import SchoolCardIcon from "@/js/components/schools/SchoolCardIcon.vue";


const props = defineProps({
    techList:{
        type: Array,
        required: true
    }
})

</script>
<template>
    <div
        v-for="(tech,index) in props.techList"
        :key="index"
    >
        <SchoolCardIcon
            :tech-name="tech.name"
            :tech-info="tech"
        />
    </div>
</template>