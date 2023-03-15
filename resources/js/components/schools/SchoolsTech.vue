<script setup>
const props = defineProps({
    techHover: {
        type: Object,
        required: true
    }
})
</script>

<template> 
    <div class="absolute left-0 px-[24px] pt-[18px]">
        <h5 class="hidden group-hover/tech:inline-block text-[16px] font-bold">
            {{ techHover.category }}
        </h5>
        <div class="schools-tech_description">
            <p><strong>{{ techHover.name }}</strong> {{ techHover.description }}</p>
        </div>
    </div>
</template>

<style>
    .schools-tech_description {
        display: none;
    }
    .schools-tech:hover .schools-tech_description {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 6;
        -webkit-box-orient: vertical;
    }
</style>
