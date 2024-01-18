<script setup>
const filterList = defineModel()

const selectedList = defineModel('selected')

const props = defineProps({
    title: {
        type: String,
        required: true
    }
})
</script>

<template>
    <div class="border-b-[1px] border-slate-500 fitlerTitle font-semibold mb-2 mt-4 text-center w-full">
        {{ title }}
    </div>
    <div
        v-for="(filter,index) in filterList"
        :key="index"
        class="flex-start w-full"
    >
        <input
            :id="filter + index"
            v-model="selectedList"
            type="checkbox"
            class="border-2 catalogueCategoryItem mx-4 rounded"
            :name="filter"
            :value="filter"
        >
        <label :for="filter + index"> {{ filter }}</label>
    </div>
</template>
