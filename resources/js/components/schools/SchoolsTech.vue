<script setup>
import {computed, ref} from "vue";

const props = defineProps({
    techHover: {
        type: Object,
        required: true
    },
    techIndex: {
        type: Number,
        required: false,
        default: 1
    }
});

</script>

<template>
    <div
        class="absolute left-0 px-[24px] schoolTechDescInner"
        style="pointer-events: none;"
    >
        <h5 class="font-bold group-hover/tech:inline-block hidden pb-2 text-[20px] text-blue-900">
            {{ techHover.category }}
        </h5>
        <div class="schoolTechDescription schools-tech_description">
            <p><strong>{{ techHover.name }}</strong> {{ techHover.description }}</p>
        </div>
    </div>
</template>

<style scoped>

.schoolTechDescription{
    font-size: 16px;
}


</style>