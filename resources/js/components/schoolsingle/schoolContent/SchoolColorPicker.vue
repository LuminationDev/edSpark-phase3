<script setup>

import { schoolColorTheme} from "@/js/constants/schoolColorTheme";

const emits = defineEmits(['colorSelected'])
const handleColorBoxClick = (color) => {
    emits('colorSelected' , color)
}
</script>
<template>
    <div class="bg-slate-100 border-2 flex flex-col max-w-xl schoolColorPickerContainer text-center w-1/2">
        <h3 class="font-semibold text-xl">
            Pick a color theme
        </h3>
        <div class="flex justify-center flex-row flex-wrap">
            <div
                v-for="(color,index) in Object.keys(schoolColorTheme)"
                :key="index"
                class="colorBox flex-wrap h-12 m-1 w-12 hover:ring-2"
                :class="`bg-[${schoolColorTheme[color]['med']}]`"
                @click="() => handleColorBoxClick(color)"
            />
        </div>
    </div>
</template>