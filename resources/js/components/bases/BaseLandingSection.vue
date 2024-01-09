<script setup>
import {computed} from "vue";

const props = defineProps({
    backgroundColor: {
        type: String,
        required: false,
        default: 'teal'
    }
})


const shadyBackgroudColorClass = computed(() => {
    switch (props.backgroundColor) {
    case 'white':
        return 'bg-white'
    case 'teal':
        return 'bg-main-teal/10'
    case 'darkTeal':
        return 'bg-main-darkTeal/20'
    case 'navy':
        return 'bg-main-navy/20'
    case 'purple':
        return 'bg-secondary-grapeDark/20'
    default:
        return 'teal'
    }
})
</script>

<template>
    <div
        class="flex flex-col my-4 px-1 py-16 lg:!px-16"
        :class="shadyBackgroudColorClass"
    >
        <div class="flex justify-between items-start flex-row mb-4 sectionHeader w-full">
            <div class="flex flex-col titleAndSubtitle">
                <div class="font-medium mb-4 text-4xl">
                    <slot name="title" />
                </div>
                <div class="font-base text-lg">
                    <slot name="subtitle" />
                </div>
            </div>
            <div class="flex justify-start items-start">
                <slot name="button" />
            </div>
        </div>
        <div class="UserActionBa w-full">
            <slot name="sectionAction" />
        </div>
        <div class="sectionContent">
            <slot name="content" />
        </div>
    </div>
</template>
