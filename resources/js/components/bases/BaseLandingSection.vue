<script setup>
import {computed} from "vue";

const props = defineProps({
    backgroundColor: {
        type: String,
        required: false,
        default: 'teal'
    }
})


const shadyBackgroundColorClass = computed(() => {
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
    case 'blue':
        return 'bg-secondary-blueberry/10'
    default:
        return 'teal'
    }
})
</script>

<template>
    <div
        class="flex justify-center flex-col px-8 py-16 xl:!px-16"
        :class="shadyBackgroundColorClass"
    >
        <div
            class="flex justify-between items-center sm:items-start flex-col justify-center gap-8 sm:flex-row sectionHeader w-full"
            :class="{'mb-8' : $slots.content}"
        >
            <div class="flex flex-col titleAndSubtitle w-full">
                <div
                    class="font-medium text-3xl sm:text-4xl"
                    :class="{'mb-4' : $slots.subtitle}"
                >
                    <slot name="title" />
                </div>
                <div
                    v-if="$slots.subtitle"
                    class="font-base text-lg"
                >
                    <slot name="subtitle" />
                </div>
            </div>
            <div class="flex justify-start items-start w-full sm:w-64 [&>button]:w-full sm:[&>button]:w-64">
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
