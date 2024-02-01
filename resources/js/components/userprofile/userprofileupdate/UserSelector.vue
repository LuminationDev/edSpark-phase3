<script setup lang="ts">
import {computed, defineEmits,defineProps, ref} from "vue";

import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";

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

const colorTheme = ref("peach");
const selectedValues = ref(props.selectedItems);

const emits = defineEmits(["sendSelectedValues"]);

const customBackground = computed(() => {
    if (schoolColorKeys.includes(colorTheme)) {
        return `bg-[${schoolColorTheme[colorTheme]["light"]}]`;
    } else {
        return `bg-[${schoolColorTheme["peach"]["light"]}] fill
        -[${schoolColorTheme["peach"]["med"]}]`;
    }
});

const handleClickItem = (itemName) =>{
    if(selectedValues.value.includes(itemName)){
        selectedValues.value = selectedValues.value.filter(item => item != itemName)
    } else {
        selectedValues.value.push(itemName)
    }


    emitNewSubjectsToParent()
}

const emitNewSubjectsToParent = () =>{
    emits('sendSelectedValues', selectedValues)
}

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
        class="cursor-pointer h-full mr-6 mt-4 p-2 rounded-xl text-white w-full hover:!bg-adminTeal hover:shadow-2xl"
        :class="customBackground"
    >
        <div
            class="p-2 text-lg"
            :class="selectedValueBackgroundClass(item.name)"
            @click="() => handleClickItem(item.name)"
        >
            <img
                :src="item.svg"
                class="items-center"
            >
        </div>
    </div>
</template>

<style scoped>
.selected {
    background-color: lightblue;
}
</style>
