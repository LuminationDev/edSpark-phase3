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
    },
    showFirstTech: {
        type: Boolean,
        required: false,
        default: false
    }
});
const computedProps = computed(() => {
    console.log('here is computed called')
    console.log(props.showFirstTech)
    return props.showFirstTech

})

</script>

<template>
    <div class="schoolTechContent absolute left-0 px-[24px] pt-[18px]">
        <h5 class="hidden group-hover/tech:inline-block text-[16px] font-bold">
            {{ techHover.category }}
        </h5>
        <div
            class="schools-tech_description schoolTechDescription"
        >
            <p><strong>{{ techHover.name }}</strong> {{ techHover.description }}</p>
        </div>
    </div>
    <div
        v-if="props.showFirstTech && props.techIndex === 0"
        class="firstTimerContainer absolute flex flex-col left-0 px-[24px] pt-[18px]"
    >
        <h5 class="text-[16px] font-bold">
            {{ techHover.category }}
        </h5>
        <div
            class="schoolTechDescription"
        >
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
.schoolTechDescription{
    font-size: 16px;

}


</style>