<script setup lang="ts">
import {computed, defineEmits,defineProps, ref} from "vue";

import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";

//important props used to import data from UserProfileSelectionMenu
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
});
const emits = defineEmits(["sendSelectedValues"]);

const colorTheme = ref("peach");
//saving value from the props.selectedItems
const selectedValues = ref(props.selectedItems);

//give custom background to the layout
const customBackground = computed(() => {
    if (schoolColorKeys.includes(colorTheme)) {
        return `bg-[${schoolColorTheme[colorTheme]["light"]}]`;
    } else {
        return `bg-[${schoolColorTheme["peach"]["light"]}] fill
        -[${schoolColorTheme["peach"]["med"]}]`;
    }
});

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
const selectedValueBackgroundClass = (item) => {
    if(selectedValues.value.includes(item)){
        return 'bg-yellow-400'
    } else{
        return ''
    }
}

</script>

<template>
    <div
        v-for="(item, index) in availableItems"
        :key="index"
        class="cursor-pointer h-full rounded-2xl text-white w-full hover:!bg-adminTeal hover:shadow-2xl"
        :class="customBackground"
    >
        <div
            class="rounded-2xl text-lg"
            :class="selectedValueBackgroundClass(item.name)"
            @click="() => handleClickItem(item.name)"
        >
            <img
                :src="item.svg"
                class="items-center p-6"
            >
        </div>
    </div>
<!--    <pre>{{ selectedValues }}</pre>-->
</template>

<style scoped>
.selected {
    background-color: lightblue;
}
</style>
