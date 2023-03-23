<script setup>
import ContentSection from "@/js/components/global/ContentSection.vue";
import SchoolCardIconList from "@/js/components/schools/SchoolCardIconList.vue";


const props = defineProps({
    schoolData:{
        type: Object ,
        required: true
    },
    linkTarget:{
        type: String,
        required: false
    }
})

/**
 * SchoolData props: {
 *     content_blocks: Object,
 *     cover_image: string (link),
 *     id: number,
 *     logo: string(link),
 *     owner : Object {
 *         owner_id : number,
 *         owner_name: string
 *     }
 *     pedagogical_approaches: {
 *         Object -- EditorJs
 *     },
 *     site: Object,
 *     tech_landscape: Object,
 *     tech_used: Array<Object>
 *
 * }
 */


</script>
<template>
    <router-link :to="`/schools/${props.schoolData.name}`">
        <ContentSection :tech-used="props.schoolData.tech_used">
            <template #cover>
                <div
                    :class="`bg-[url('${props.schoolData.cover_image}')] rounded-t-xl`"
                    class="h-36 group-hover:h-0 transition-all"
                />
            </template>
            <template #title>
                {{ props.schoolData.name }}
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
