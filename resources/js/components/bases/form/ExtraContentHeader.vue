<script setup>
import Trash from "@/js/components/svg/Trash.vue";

const props = defineProps({
    clickCallback:{
        type: Function,
        required: true
    }
})
</script>

<template>
    <div class="bg-main-teal flex justify-between items-center flex-row formExtraContentHeader h-10 mb-2 px-4 rounded-t-xl text-base text-white">
        <div class="formExtraContentHeading">
            <slot name="headingLeft" />
        </div>
        <div class="formExtraContentDelete">
            <Trash
                class="h-4 mr-4 w-4 hover:cursor-pointer hover:stroke-secondary-mbRose"
                @click="props.clickCallback"
            />
        </div>
    </div>
</template>