<script setup>
import CardLoading from "@/js/components/card/CardLoading.vue";
import {getNRandomElementsFromArray} from "@/js/helpers/cardDataHelper";

const props = defineProps({
    resourceList:{
        type: Array,
        required: true
    }
})
</script>
<template>
    <div class="BaseLandingCardRowContainer grid grid-cols-1 gap-10 place-items-center mt-10 md:!grid-cols-2 lg:!grid-cols-3">
        <template v-if="!props.resourceList || !props.resourceList.length">
            <div
                class="col-span-1 md:!col-span-2 lg:!col-span-3"
            >
                <CardLoading
                    :number-of-rows="1"
                    :number-per-row="3"
                />
            </div>
        </template>
        <template v-else>
            <slot name="rowContent" />
        </template>
    </div>
</template>
