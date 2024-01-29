<script setup>
import {computed, onBeforeMount, onMounted,ref} from 'vue'

import {schoolColorKeys,schoolColorTheme} from "@/js/constants/schoolColorTheme";

const props = defineProps({
    title: {
        type: String, required: true
    },
    listData: {
        type: Array,
        required: true
    },
    existingData: {
        type: Array,
        required: false
    },
    colorTheme: {
        type: String,
        required: false
    }
})

const result = ref()

onBeforeMount(() => {
    if (props.existingData) {
        result.value = props.existingData
    }
})

const emits = defineEmits(['sendDataToParent'])

/**
 * pass in item object to the function and will check if exists based on ID
 * very generic function with the aim to be reusable
 */
const handleSelectItem = (itemObj) => {
    if (checkIfObjectIsSelected(itemObj)) {
        // remove the item from array
        result.value = result.value.filter(item => item.name !== itemObj.name)
    } else {
        result.value.push(itemObj)
    }
    emits('sendDataToParent', result.value)
}

const checkIfObjectIsSelected = (itemObj) => {
    return result.value.map(item => item.name).includes(itemObj.name)
}


const customBackground = computed(() => {
    if (schoolColorKeys.includes(props.colorTheme)) {
        return `bg-[${schoolColorTheme[props.colorTheme]['light']}] fill-[${schoolColorTheme[props.colorTheme]['med']}]`;
    } else {
        return `bg-[${schoolColorTheme['teal']['light']}] fill-[${schoolColorTheme['teal']['med']}]`;
    }
})

</script>
<template>
    <div class="genericSelectorContainer">
        <div class="font-semibold text-lg underline">
            {{ props.title }}
        </div>
        <div
            v-for="(item,index) in listData"
            :key="index"
            class="cursor-pointer grayscale group h-28 my-2 opacity-60 p-2 rounded-xl text-white hover:shadow-xl"
            :class="[{'!grayscale-0 !opacity-100' : checkIfObjectIsSelected(item)}, customBackground]"

            @click="() => handleSelectItem(item)"
        >
            <slot
                :name="'selectorItem_' + index"
                :item-data="item"
                :color-theme="colorTheme"
            />
        </div>
    </div>
</template>
