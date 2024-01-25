<script setup>
import {computed} from 'vue'

import AdviceCaseStudyIcon from "@/js/components/svg/AdviceCaseStudyIcon.vue";
import IconDAG from "@/js/components/svg/AdviceDAG.vue";
import IconOther from "@/js/components/svg/AdviceOther.vue";
import IconWork from "@/js/components/svg/AdviceWork.vue";
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";

const props = defineProps({
    typeTag: {
        type: Array, required: true
    }
})


const typeTagColor = computed(() => {
    if (props.typeTag[0] === 'DAG') {
        return 'text-main-teal border-main-teal bg-main-teal/10 [&>svg]:fill-main-teal [&>svg]:stroke-main-teal';
    } else {
        return 'text-secondary-bananadark border-secondary-bananadark bg-secondary-banana/10 [&>svg]:fill-secondary-bananadark/10 [&>svg]:stroke-secondary-bananadark';
    }
})
</script>


<template>
    <div
        class="TypeTag border border-solid flex gap-2 h-[28px] mx-3 my-2 place-items-start px-2 py-1 rounded-2xl text-sm w-fit"
        :class="typeTagColor"
    >
        <template v-if="props.typeTag[0] === 'DAG'">
            <IconDAG />
        </template>

        <template v-else-if="props.typeTag[0] === 'Your Work'">
            <IconWork />
        </template>
        <template v-else-if="props.typeTag[0] === 'Case Study'">
            <AdviceCaseStudyIcon />
        </template>

        <template v-else>
            <IconOther />
        </template>

        <p class="whitespace-nowrap">
            {{ typeTag[0] }}
        </p>
    </div>
</template>


