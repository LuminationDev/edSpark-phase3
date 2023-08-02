<script setup>
import {ref, computed} from 'vue'

const props = defineProps({

})

const emits = defineEmits([])
</script>

<template>
    <div class="bg-white flex py-4 w-full">
        <ul
            class="
                flex
                justify-start
                items-start
                flex-col
                font-medium
                pl-4
                text-base
                text-main-navy
                md:!flex-row
                md:!items-center
                md:!justify-center
                ">
            <li class="cursor-pointer hover:text-[#4a87c0] md:px-2">
                Accessibility
            </li>
            <li class="hidden px-2 md:!block">
                <div class="bg-[#0E4376] h-[20px] w-[1px]" />
            </li>
            <li class="cursor-pointer hover:text-[#4a87c0] md:px-2">
                Acknowledgement of Country
            </li>
            <li class="hidden px-2 md:!block">
                <div class="bg-[#0E4376] h-[20px] w-[1px]" />
            </li>
            <li class="cursor-pointer hover:text-[#4a87c0] md:px-2">
                Copyright
            </li>
            <li class="hidden px-2 md:!block">
                <div class="bg-[#0E4376] h-[20px] w-[1px]" />
            </li>
            <li class="cursor-pointer hover:text-[#4a87c0] md:px-2">
                Disclaimer
            </li>
            <li class="hidden px-2 md:!block">
                <div class="bg-[#0E4376] h-[20px] w-[1px]" />
            </li>
            <li class="cursor-pointer  hover:text-[#4a87c0] md:px-2">
                Privacy
            </li>
        </ul>
    </div>
</template>
