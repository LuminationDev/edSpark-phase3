<script setup>

import {schoolColorTheme} from "@/js/constants/schoolColorTheme";

const emits = defineEmits(['colorSelected'])
const handleColorBoxClick = (color) => {
    emits('colorSelected', color)
}
</script>
<template>
    <div class="bg-slate-100 border-2 flex flex-col max-w-xl px-4 py-2 schoolColorPickerContainer text-center w-full  xl:!w-3/4">
        <h3 class="font-semibold text-xl">
            Pick a colour theme
        </h3>
        <div class="flex justify-center flex-row flex-wrap">
            <template v-for="(color,index) in Object.keys(schoolColorTheme)">
                <div
                    v-if="!color.includes('event') && !color.includes('dark') && !color.includes('partner') && !color.includes('hardware') && !color.includes('software') && !color.includes('technology')"
                    :key="index"
                    class="colorBox flex-wrap h-12 m-1 w-12 hover:ring-2"
                    :class="`bg-[${schoolColorTheme[color]['med']}]`"
                    @click="() => handleColorBoxClick(color)"
                />
            </template>
        </div>
    </div>
</template>