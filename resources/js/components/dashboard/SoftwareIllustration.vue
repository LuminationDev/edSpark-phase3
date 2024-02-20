<script setup>


import {onMounted, onUnmounted, ref} from "vue";

import DepartmentApprovedSolo from "@/js/components/svg/DepartmentApprovedSolo.vue";
import DeptApprovedIcon from "@/js/components/svg/software/DeptApprovedIcon.vue";
import DeptProvidedIcon from "@/js/components/svg/software/DeptProvidedIcon.vue";
import {getDistanceBetweenElements} from "@/js/helpers/drawingHelpers";

/**
 * Handling line length on the software dashboard highlights
 */
const top = ref('');
const distanceBetweenEls = ref('');
const floatingLineClasses = ref('');


const adjustConnectingLinePositions = () => {
    const listContainers = document.querySelectorAll('.softwareDashboardContentContainer');

    if (listContainers.length === 0) return; // Exit early if no containers found

    const [firstContainer] = listContainers;
    const lastContainer = listContainers[listContainers.length - 1];

    distanceBetweenEls.value = getDistanceBetweenElements(
        firstContainer,
        lastContainer
    );

    const firstElHeight = firstContainer.offsetHeight;
    top.value = firstContainer.offsetTop + firstElHeight / 2;
    floatingLineClasses.value = `top-[${top.value}] h-[${distanceBetweenEls.value}px]`;
};
const handleResize = () => adjustConnectingLinePositions()

const debouncedHandleScroll = _.debounce(() => {
    adjustConnectingLinePositions();
}, 100);


onMounted(async () => {
    window.addEventListener('resize', handleResize);
    handleResize();
    window.addEventListener('scroll', debouncedHandleScroll);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
    window.removeEventListener('scroll',debouncedHandleScroll)
});
</script>

<template>
    <div class="grid grid-cols-6 gap-x-8 place-items-center relative lg:!place-items-start">
        <div
            class="bg-white border-[4px] border-black col-span-2 flex h-[100px] my-auto relative rounded-full w-[100px]"
        >
            <div
                class="absolute left-1/2 border-[2px] border-black connectingLine z-[-1]"
                :style="`height: ${distanceBetweenEls}px; top: ${top-40}px;`"
            />
            <DeptProvidedIcon class="h-full p-1" />
        </div>
        <div
            class="col-span-4 flex justify-center flex flex-col h-1/2 min-h-[250px] my-auto softwareDashboardContentContainer"
        >
            <h5 class="font-semibold pt-4 text-xl">
                No cost
            </h5>
            <p class="">
                These are applications are available with no cost to schools, these applications have been formally risk assessed by Cyber Security centrally.
            </p>
        </div>

        <div
            class="bg-white border-[4px] border-black col-span-2 flex h-[100px] my-auto rounded-full w-[100px]"
        >
            <DeptApprovedIcon class="h-full p-1" />
        </div>
        <div
            class="col-span-4 flex justify-center flex flex-col h-1/2 min-h-[250px] my-auto softwareDashboardContentContainer"
        >
            <h5 class="font-semibold text-xl">
                Cyber Assessed
            </h5>
            <p class="pb-4">
                These are applications that have been risk assessed by Cyber Security but are not provided by the Department, site leaders should be aware of the risks prior to being used at your school.
            </p>
        </div>

        <div
            class="bg-white border-[2px] border-black border-dashed col-span-2 flex h-[100px] my-auto relative rounded-full w-[100px]"
        >
            <!--            <DeptApprovedIcon class="h-full p-1" />-->
            <DepartmentApprovedSolo
                class="h-full w-full"
            />
        </div>
        <div
            class="col-span-4 flex justify-center flex flex-col h-1/2 min-h-[250px] my-auto softwareDashboardContentContainer"
        >
            <h5 class="font-semibold text-xl">
                Negotiated Deals
            </h5>
            <p class="pb-4">
                Still risk assessed, these applications have an agreement
                in place for schools to receive better value. Schools will
                need to fund purchases
            </p>
        </div>
    </div>
</template>
<style scoped lang="scss">
.negotiatedDealsLine {
    width: 2px;
    background: linear-gradient(to top, transparent, #000);
    /* Fading effect to transparent */
}

.negotiatedDealsLine::before {
    content: "";
    position: absolute;

}
</style>