<script setup>
import {ref} from "vue";

import ChevronDownIcon from "@/js/components/svg/chevron/ChevronDownIcon.vue";

const filterList = defineModel()
const selectedList = defineModel('selected')

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    defaultShowFilter :{
        type: Boolean,
        required: false,
        default: false
    }
})

const isFilterShown = ref(props.defaultShowFilter)

const toggleShowFilter = () => {
    isFilterShown.value = !isFilterShown.value
}
</script>

<template>
    <div class="fitlerHeader flex flex-row mb-2 mt-8 text-lg w-full">
        <div class="filterTitle">
            {{ title }}
        </div>
        <div class="ml-auto">
            <ChevronDownIcon
                class="cursor-pointer transition-transform"
                :class="{'rotate-180' : isFilterShown}"
                @click="toggleShowFilter"
            />
        </div>
    </div>
    <div
        class="flex flex-col transition-all"
    >
        <TransitionGroup>
            <template
                v-if="isFilterShown"
            >
                <div
                    v-for="(filter,index) in filterList"
                    :key="index"
                    class="flex items-center flex-row h-full mt-2 text-lg w-full"
                >
                    <input
                        :id="filter + index"
                        v-model="selectedList"
                        type="checkbox"
                        class="!p-2.5 border-[1px] border-slate-300 catalogueCategoryItem mr-3 rounded shadow"
                        :name="filter"
                        :value="filter"
                    >
                    <label
                        class="flex h-full"
                        :for="filter + index"
                    > {{ filter }}</label>
                </div>
            </template>
        </TransitionGroup>
    </div>
</template>
