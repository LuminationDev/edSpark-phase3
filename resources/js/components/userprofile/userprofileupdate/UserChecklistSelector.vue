<script setup lang="ts">
import {defineEmits, defineProps, ref, watch} from "vue";

const props = defineProps({
    availableItems:{
        type: Array,
        required: true
    },
    selectedItems:{
        type: Array,
        required: false,
        default: () => []
    }
})
const emits = defineEmits(["sendSelectedValues"])

watch(() => props.selectedItems, (newValue) => {
    console.log(newValue)
    selectedValues.value = newValue
});

//saving value from the props.selectedItems
const selectedValues = ref(props.selectedItems)
const emitNewItemsToParent = () =>{
    emits('sendSelectedValues', selectedValues.value)
}

//function to handle click item and then sends listing items array to the UserProfileSelectionMenu
const handleClickItem = (itemName) =>{
    if(selectedValues.value.includes(itemName)){
        selectedValues.value = selectedValues.value.filter(item => item != itemName)
    } else {
        selectedValues.value.push(itemName)
    }
    emitNewItemsToParent()
}
// To deteremine the item is selected for checkbox
const isSelected = (item) => selectedValues.value.includes(item.name)

// To get the checkbox state for an item
const getCheckboxState = (item) => isSelected(item)
</script>

<template>
    <div class="grid grid-cols-3 mt-6 userYearLevelSelectorContainer sm:!grid-cols-5 md:!grid-cols-6">
        <div
            v-for="(item, index) in availableItems"
            :key="index"
            class="cursor-pointer flex flex-row gap-6 mb-10 w-[7em]"
            @click="() => handleClickItem(item.name)"
        >
            <div class="CheckListSelector mt-1">
                <input
                    :checked="getCheckboxState(item)"
                    type="checkbox"
                    class="absolute bg-gray-10 dark:bg-gray-700 border-gray-300 dark:border-gray-600 h-5 rounded w-5"
                    style="color: #0A7982;"
                >
            </div>
            <div class="align-right ml-3 text-lg">
                {{ item.name }}
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
