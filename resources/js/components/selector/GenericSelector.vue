<script setup>
import {onBeforeMount, ref, computed} from 'vue'
import { schoolColorTheme, schoolColorKeys} from "@/js/constants/schoolColorTheme";

const props = defineProps({
    title:{
        type: String, required: true
    },
    listData:{
        type: Array,
        required: true
    },
    existingData:{
        type: Array,
        required: false
    },
    colorTheme:{
        type: String, required: false
    }
})

const result = ref()

onBeforeMount(() => {
    if(props.existingData){
        result.value = props.existingData
    }
})

const emits = defineEmits(['sendDataToParent'])


/**
 * pass in item object to the function and will check if exists based on ID
 * very generic function with the aim to be reusable
 */
const handleSelectItem = (itemObj) => {
    if (checkIfObjectIsSelected(itemObj)){
        // remove the item from array
        result.value = result.value.filter(item => item.name !== itemObj.name)
    } else{
        result.value.push(itemObj)
    }
    emits('sendDataToParent', result.value)
}

const checkIfObjectIsSelected = (itemObj) => {
    return result.value.map(item => item.name).includes(itemObj.name)
}

const customBackground = computed(() => {
    return `!bg-[${schoolColorTheme[props.colorTheme]['light']}]`
})


</script>
<template>
    <div class="genericSelectorContainer">
        <div
            v-for="(item,index) in listData"
            :key="index"
            class=" h-28 p-2 my-2 bg-blue-300 text-white rounded-xl grayscale opacity-60"
            :class="[{'bg-blue-300 !grayscale-0 !opacity-100' : checkIfObjectIsSelected(item)}, customBackground]"


            @click="() => handleSelectItem(item)"
        >
            <slot
                :name="'selectorItem_' + index"
                :item-data="item"
            />
        </div>
    </div>
</template>




<!--
Piping
let check = [{name: 'trent'}, {name: 'jason'}]
  .map(item => item.name)
  .includes('trent');

Simple & fast
let check = [{name: 'trent'}, {name: 'jason'}].some(el => el.name === 'trent')-->