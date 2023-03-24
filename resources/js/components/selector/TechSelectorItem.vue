<script setup>
import SchoolTechIconGenerator from "@/js/components/global/SchoolTechIconGenerator.vue";

const props = defineProps({
    item: {
        type: Object, required: true
    }
})
</script>
<template>
    <div class="h-full p-2 text-black rounded-xl flex flex-row ">
        <div class="basis-1/3">
            <SchoolTechIconGenerator
                :tech-name="item.name"
                class="min-w-[60px] pr-4 m-2"
            />
        </div>
        <div class="basis-2/3 overflow-scroll">
            <p class="line-clamp-3 ">
                {{ `${item.name} ${item.description}` }}
            </p>
        </div>
    </div>
</template>