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
    showFirstTech:{
        type: Boolean,
        required: false,
        default: false
    }
})
const showFirstItem = ref(true)


const showFirstComputed = computed(() => {
    return !!(props.showFirstTech && showFirstItem.value);
})

</script>

<template>
    <div
        v-for="(tech,index) in props.techList"
        :key="index"
        class="card_inner_parent cursor-pointer"
        @mouseenter="showFirstItem = false"
        @mouseleave="showFirstItem = true"
    >
        <SchoolCardIcon
            :tech-name="tech.name"
            :tech-info="tech"
            :tech-index="index"
            :show-first-tech="showFirstComputed"
        />
    </div>
</template>
<style>

</style>

