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
    <div class="absolute left-0 pt-[18px] px-[24px] schoolTechContent">
        <h5 class="font-bold group-hover/tech:inline-block hidden text-[16px]">
            {{ techHover.category }}
        </h5>
        <div
            class="schoolTechDescription schools-tech_description"
        >
            <p><strong>{{ techHover.name }}</strong> {{ techHover.description }}</p>
        </div>
    </div>
    <div
        v-if="props.showFirstTech && props.techIndex === 0"
        class="absolute left-0 firstTimerContainer flex flex-col pt-[18px] px-[24px]"
    >
        <h5 class="font-bold text-[16px]">
            {{ techHover.category }}
        </h5>
        <div
            class="schoolTechDescription"
        >
            <p><strong>{{ techHover.name }}</strong> {{ techHover.description }}</p>
        </div>
    </div>
</template>

<style scoped>
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