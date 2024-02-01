<script setup lang="ts">
import { computed, ref, defineProps, defineEmits } from "vue";
import { schoolColorKeys, schoolColorTheme } from "@/js/constants/schoolColorTheme";

const colorTheme = ref("peach");
const selectedValues = ref([]);
const props = defineProps({
    listData: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: Number,
        default: null,
    },
});

const emits = defineEmits(["storeItemName", "update:modelValue", "sendSelectedValues"]);

const customBackground = computed(() => {
    if (schoolColorKeys.includes(colorTheme)) {
        return `bg-[${schoolColorTheme[colorTheme]["light"]}]`;
    } else {
        return `bg-[${schoolColorTheme["peach"]["light"]}] fill
        -[${schoolColorTheme["peach"]["med"]}]`;
    }
});

const handleItemClick = (item, index) => {
    // Toggle the selected state for the clicked item
    selectedValues.value[index] = !selectedValues.value[index]

    // Filter out the selected items and emit to the parent component
    const selectedItems = props.listData
        .filter((item, index) => selectedValues.value[index])
        .map((item) => item.name)

    emits("storeItemName", selectedItems)
    emits("sendSelectedValues", selectedValues.value[index])
};
</script>

<template>
    <div
        v-for="(item, index) in props.listData"
        :key="index"
        class="cursor-pointer mr-6 mt-4 h-full w-full p-2 rounded-xl text-white hover:!bg-adminTeal hover:shadow-2xl"
        :class="customBackground"
    >
        <div
            class="p-2 text-lg"
            @click="() => handleItemClick(item, index)"
            :class="{ 'bg-yellow-400': selectedValues[index] }"
        >
            <img :src='item.svg' class="items-center">
        </div>
    </div>
</template>

<style scoped>
.selected {
    background-color: lightblue;
}
</style>
