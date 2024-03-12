<script setup>
import {computed} from "vue";

import CardLoading from "@/js/components/card/CardLoading.vue";
import {useWindowStore} from "@/js/stores/useWindowStore";

const props = defineProps({
    resourceList: {
        type: Array,
        required: true
    }
})

const windowStore = useWindowStore()

const isLoading = computed(() => {
    return !props.resourceList || !props.resourceList.length
})

</script>
<template>
    <div
        class="BaseLandingCardRowContainer grid grid-cols-1 gap-10 place-items-center  md:!grid-cols-2 lg:!grid-cols-3"
    >
        <template v-if="isLoading">
            <div
                class="col-span-1 w-full md:!col-span-2 lg:!col-span-3"
            >
                <CardLoading
                    :number-of-rows="1"
                    :number-per-row="windowStore.getNumberOfCardLoading"
                />
            </div>
        </template>
        <template v-else>
            <slot name="rowContent"/>
        </template>
    </div>
</template>
