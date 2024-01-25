<script setup>

import {computed} from "vue";

import AdviceCard from "@/js/components/advice/AdviceCard.vue";
import CardLoading from "@/js/components/card/CardLoading.vue";
import {getNRandomElementsFromArray} from "@/js/helpers/cardDataHelper";
const props = defineProps({
    adviceList:{
        type: Array,
        required: true
    }
})
const CaseStudyAdviceList = computed(() =>{
    if(!props.adviceList || props.adviceList.length < 1 ) return []
    return props.adviceList.filter(advice => advice.type[0] === 'Case Study')
})
</script>

<template>
    <div class="EduAdviceCards grid grid-cols-1 gap-10 place-items-center mt-10 md:!grid-cols-2 lg:!grid-cols-3">
        <template v-if="props.adviceList && props.adviceList.length">
            <AdviceCard
                v-for="advice in getNRandomElementsFromArray(CaseStudyAdviceList,3)"
                :key="advice.guid"
                :data="advice"
                :show-icon="true"
            />
        </template>
        <template v-else>
            <div
                class="col-span-1 md:!col-span-2 lg:!col-span-3"
            >
                <CardLoading
                    :number-of-rows="1"
                    :number-per-row="3"
                />
            </div>
        </template>
    </div>
</template>
