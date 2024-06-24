<script setup>
import {computed, onMounted, ref} from 'vue'

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {adviceService} from "@/js/service/adviceService";

const props = defineProps({

})

const learningTasks = ref([])
const filterObject = ref({})

onMounted(async () =>{
    learningTasks.value = await adviceService.fetchLearningTask()
})

const emits = defineEmits([])
</script>

<template>
    <div>
        <BaseSearch
            search-type="task"
            :resource-list="learningTasks"
            :hero-title="LandingHeroText['learningTask']['title']"
            :hero-subtitle="LandingHeroText['learningTask']['subtitle']"
            :live-filter-object="filterObject"
        />
    </div>
</template>
