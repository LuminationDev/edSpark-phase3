<script setup>
import {computed, onMounted, ref} from 'vue'

import BaseSearch from "@/js/components/search/BaseSearch.vue";
import {LandingHeroText} from "@/js/constants/PageBlurb";
import {adviceService} from "@/js/service/adviceService";

const props = defineProps({

})

const emits = defineEmits([])
const dags = ref([])
const filterObject = ref({})



onMounted(async () =>{
    dags.value = await adviceService.fetchDAG()
})


</script>

<template>
    <div>
        <BaseSearch
            search-type="dag"
            :resource-list="dags"
            :hero-title="LandingHeroText['dag']['title']"
            :hero-subtitle="LandingHeroText['dag']['subtitle']"
            :live-filter-object="filterObject"
        />
    </div>
</template>
