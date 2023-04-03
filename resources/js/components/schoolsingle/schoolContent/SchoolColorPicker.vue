<script setup>

import { schoolColorTheme} from "@/js/constants/schoolColorTheme";

const emits = defineEmits(['colorSelected'])
const handleColorBoxClick = (color) => {
    emits('colorSelected' , color)
}
</script>
<template>
    <div class="schoolColorPickerContainer max-w-xl w-1/2 bg-slate-100 flex flex-col text-center border-2">
        <h3 class="font-semibold text-xl ">
            Pick a color theme
        </h3>
        <div class="flex flex-row flex-wrap justify-center">
            <div
                v-for="(color,index) in Object.keys(schoolColorTheme)"
                :key="index"
                class="colorBox w-12 h-12 m-1 flex-wrap hover:ring-2"
                :class="`bg-[${schoolColorTheme[color]['med']}]`"

                @click="() => handleColorBoxClick(color)"
            />
        </div>
    </div>
</template>