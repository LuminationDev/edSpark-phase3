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
                w-full
                md:!flex-row
                md:!items-center
                md:!justify-center
                ">
            <li class="cursor-pointer hover:text-[#4a87c0] md:px-2">
                <a href="https://www.education.sa.gov.au/accessibility">
                    Accessibility
                </a>
            </li>
            <li class="hidden px-2 md:!block">
                <div class="bg-[#0E4376] h-[20px] w-[1px]" />
            </li>
            <li class="cursor-pointer hover:text-[#4a87c0] md:px-2">
                <a href="https://www.education.sa.gov.au/acknowledgement-of-country">
                    Acknowledgement of Country
                </a>
            </li>
            <li class="hidden px-2 md:!block">
                <div class="bg-[#0E4376] h-[20px] w-[1px]" />
            </li>
            <li class="cursor-pointer hover:text-[#4a87c0] md:px-2">
                <a href="https://www.education.sa.gov.au/copyright">
                    Copyright

                </a>
            </li>
            <li class="hidden px-2 md:!block">
                <div class="bg-[#0E4376] h-[20px] w-[1px]" />
            </li>
            <li class="cursor-pointer hover:text-[#4a87c0] md:px-2">
                <a href="https://www.education.sa.gov.au/disclaimer">
                    Disclaimer
                </a>
            </li>
            <li class="hidden px-2 md:!block">
                <div class="bg-[#0E4376] h-[20px] w-[1px]" />
            </li>
            <li class="cursor-pointer  hover:text-[#4a87c0] md:px-2">
                <a href="https://www.education.sa.gov.au/your-privacy">
                    Privacy
                </a>
            </li>
        </ul>
    </div>
</template>
