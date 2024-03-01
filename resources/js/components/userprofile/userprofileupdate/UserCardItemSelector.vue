<script setup lang="ts">
import {defineEmits, defineProps, ref} from "vue";

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
})

const emits = defineEmits(["sendSelectedValues"])

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

const selectedValueBackgroundClass = (item) => {
    if (selectedValues.value.includes(item)) {
        return 'border-[#339999] border-[2px] cursor-pointer h-36 rounded-2xl text-white w-36 ml-[-2px] mt-[-2px]'
    } else {
        return ''
    }
}
const selectedValueSvgColorClass = (item, svgCustomSelectedClass, svgCustomUnSelectedClass) => {
    if (selectedValues.value.includes(item)) {
        return `stroke-main-darkTeal ${svgCustomSelectedClass}`
    } else {
        return `stroke-[#344054] ${svgCustomUnSelectedClass}`
    }
}
const selectedValueTextColorClass = (item) => {
    if (selectedValues.value.includes(item)) {
        return 'text-main-darkTeal'
    } else {
        return `text-[#344054]`
    }
}


const isSelected = (item) => selectedValues.value.includes(item.name)


const getCheckboxState = (item) => isSelected(item)



</script>

<template>
    <div class="2xl:!grid-cols-5 grid sm:grid-cols-3 grid-cols-2 gap-2 place-items-center mb-10 md:!grid-cols-4 lg:!gap-8">
        <div
            v-for="(item, index) in availableItems"
            :key="index"
            class="
                !border-gray-250
                hover:!border-[#339999]
                border-[2px]
                cursor-pointer
                h-36
                rounded-2xl
                scale-75
                text-white
                w-36
                sm:!scale-100
                "
        >
            <div
                class="flex flex-col text-lg"
                :class="selectedValueBackgroundClass(item.name)"
                @click="() => handleClickItem(item.name)"
            >
                <div class="ml-auto mr-6 mt-2">
                    <input
                        :checked="getCheckboxState(item)"
                        type="checkbox"
                        class="absolute bg-gray-10 dark:bg-gray-700 border-gray-300 dark:border-gray-600 h-5 rounded w-5"
                        style="color: #0A7982;"
                    >
                </div>
                <div class="cardIconDynamicComponent ml-auto mr-auto p-4">
                    <component
                        :is="item.svgComponent"
                        :class="selectedValueSvgColorClass(item.name , item.svgCustomSelectedClass || '', item.svgCustomUnSelectedClass || '')"
                    />
                </div>
                <div
                    class="ml-auto mr-auto text-xl"
                    :class="selectedValueTextColorClass(item.name)"
                >
                    {{ item.name }}
                </div>
            </div>
        </div>
    </div>
<!--    <pre>{{ selectedValues }}</pre>-->
</template>

<style scoped>


</style>
