<script setup>
import {onMounted} from "vue";
import ContentSection from "@/js/components/global/ContentSection.vue";
import SchoolCardIconList from "@/js/components/schools/SchoolCardIconList.vue";


const props = defineProps({
    schoolData:{
        type: Object,
        required: true
    },
    linkTarget:{
        type: String,
        required: true
    }
})

console.log(props.schoolData)
/**
 * schoolData : Object
 * {
 *     title: string,
 *     description: string,
 *     created_at: string,
 *     cover: string
 *     techUsed: Array<{name: string, description: string, category: string}>
 * }
 */

</script>
<template>
    <router-link :to="`/schools/${props.schoolData.full_name}`">
        <ContentSection :tech-used="props.schoolData.tech_used">
            <template #cover>
                <div
                    :class="`bg-[url('${props.schoolData.cover}')]`"
                    class="h-36 group-hover:h-0 transition-all"
                />
            </template>
            <template #title>
                {{ props.schoolData.full_name }}
            </template>
            <template #techUsed="{techUsed}">
                <p class="pt-6 text-black text-[18px] font-medium">
                    Tech used:
                </p>
                <div class=" pt-4 flex flex-row w-full justify-between place-items-center ">
                    <SchoolCardIconList :tech-list="techUsed" />
                </div>
            </template>
        </ContentSection>
    </router-link>
</template>
