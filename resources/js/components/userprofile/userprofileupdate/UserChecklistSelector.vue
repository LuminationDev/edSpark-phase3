<script setup lang="ts">
import {defineEmits, defineProps, ref} from "vue";

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

//custom backgrond color for the selected items
const selectedItemBackgroundClass = (item) => {
    if(selectedValues.value.includes(item)){
        return 'bg-red-400'
    } else{
        return ''
    }
}

</script>

<template>
    <div
        v-for="(item, index) in availableItems"
        :key="index"
        class="cursor-pointer flex flex-row mb-10 w-[7em]"
    >
        <div
            class="CheckListSelector border-2 cursor-pointer h-6 mb-auto ml-2 mt-auto w-6"
            :class="selectedItemBackgroundClass(item.name)"
            @click="() => handleClickItem(item.name)"
        />
        <div class="align-right ml-4 text-lg">
            {{ item.name }}
        </div>
    </div>
</template>

<style scoped>

</style>
