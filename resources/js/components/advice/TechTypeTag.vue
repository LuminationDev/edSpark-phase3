<script setup>
import { computed } from 'vue'
import {schoolColorKeys, schoolColorTheme} from "@/js/constants/schoolColorTheme";


const props = defineProps({
    typeTag: {
        type: Array, required: true
    }
})


const typeTagColor = computed(() => {

if (props.typeTag[0] == 'DAG advice') {
    return 'text-main-teal border-main-teal bg-main-teal/10';
} else {
    return 'text-['+schoolColorTheme['banana']['dark']+'] border-secondary-banana bg-secondary-banana/10';
}
})

</script>

<template>
    <!-- class="absolute rounded bg-[#FFC836] min-w-[136px] h-[39px] text-white flex flex-row justify-around gap-3 place-items-center -right-3 top-3 px-4" -->
    <div class="TypeTag border border-solid flex gap-4 h-[28px] py-1 place-items-center px-2 rounded-2xl text-sm w-fit mx-3 my-2 !min-w-fit text-center leading-3"
        :class="typeTagColor">
        {{ typeTag[0] }}
    </div>
</template>



<!-- <script setup>
import { computed } from 'vue'
const props = defineProps({
    typeTag: {
        type: Array, required: true
    }
})

const typeTagColor = computed(() => {

    if (props.typeTag[0] == 'DAG advice') {
        return 'text-secondary-700 border-yellow-700 bg-yellow-600/5';
    } else {
        return 'text-adviceGreen border-adviceGreen bg-adviceGreen/5';
    }
})

</script>

<template>
    <div class="TypeTag border border-solid flex gap-4 h-[28px] py-1 place-items-center px-2 rounded-2xl text-sm w-fit mx-3 my-2 !min-w-fit text-center leading-3"
        :class="typeTagColor">
        {{ typeTag[0] }}
    </div>
</template> -->
