<script setup>
import Trash from "@/js/components/svg/Trash.vue";
import {isEmptyFunction} from "@/js/helpers/objectHelpers";

const props = defineProps({
    clickCallback:{
        type: Function,
        required: false,
        default: () => {}
    }
})
</script>

<template>
    <div class="bg-main-teal flex justify-between items-center flex-row formExtraContentHeader h-10 mb-2 px-4 rounded-t-xl text-base text-white">
        <div class="formExtraContentHeading">
            <slot name="headingLeft" />
        </div>
        <div
            v-if="!isEmptyFunction(props.clickCallback)"
            class="formExtraContentDelete"
        >
            <Trash
                class="h-4 mr-4 w-4 hover:cursor-pointer hover:stroke-secondary-mbRose"
                @click="props.clickCallback"
            />
        </div>
    </div>
</template>