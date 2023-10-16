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

onMounted(async () => {
    window.addEventListener('resize', handleResize);
    handleResize();
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
    <div class="flex flex-row gap-4 h-1/2 w-full">
        <div class="relative w-1/4">
            <!--  first line-->
            <div
                class="absolute left-1/2 border-[2px] border-black connectingLine z-10"
                :style="`height: ${distanceBetweenEls}px; top: ${top}px;`"
            />
            <div class="flex flex-col h-full relative z-20">
                <div class="flex h-1/2 min-h-[200px] place-self-center">
                    <div
                        class="bg-white border-[4px] border-black flex h-[100px] my-auto rounded-full w-[100px]"
                    >
                        <DeptProvidedIcon class="h-full p-1" />
                    </div>
                </div>
                <div class="flex h-1/2 min-h-[200px] place-self-center">
                    <div
                        class="bg-white border-[4px] border-black flex h-[100px] my-auto rounded-full w-[100px]"
                    >
                        <DeptApprovedIcon class="h-full p-1" />
                    </div>
                </div>
            </div>
        </div>

        <div class="h-full ml-4 w-3/4">
            <div class="flex flex-col h-full">
                <div
                    class="flex justify-center flex-col h-1/2 min-h-[200px] my-auto softwareDashboardContentContainer"
                >
                    <h5 class="font-semibold pt-4 text-xl">
                        Department Provided
                    </h5>
                    <p class="w-9/12">
                        These applications are provided by the department
                        at no cost to schools
                    </p>
                </div>
                <div
                    class="flex justify-center flex-col h-1/2 min-h-[200px] my-auto softwareDashboardContentContainer"
                >
                    <h5 class="font-semibold text-xl">
                        Department Approved
                    </h5>
                    <p class="pb-4 w-9/12">
                        These applications have been risk assessed and can
                        be safely used in schools
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-row gap-4 h-1/2 relative w-full">
        <div class="relative w-1/4">
            <div
                class="absolute left-1/2 border-[1.5px] border-black border-dashed negotiatedDealsLine z-10"
                :style="`height: ${distanceBetweenEls}px; top: calc(-${top}px + 60px);`"
            />
            <div class="flex flex-col h-full relative z-20">
                <div class="flex h-3/4 place-self-center">
                    <div
                        class="
                            bg-white
                            border-[2px]
                            border-black
                            border-dashed
                            flex
                            h-[100px]
                            my-auto
                            relative
                            rounded-full
                            w-[100px]
                            "
                    >
                        <DeptApprovedIcon class="h-full p-1" />
                        <DepartmentApprovedSolo class="absolute -top-1 -right-1 h-[40px] w-[40px]" />
                    </div>
                </div>
                <div class="flex h-1/4 place-self-center" />
            </div>
        </div>

        <div class="ml-4 w-3/4">
            <div class="flex flex-col h-full">
                <div class="flex justify-center flex-col h-3/4 min-h-[200px] my-auto">
                    <h5 class="font-semibold text-xl">
                        Negotiated Deals
                    </h5>
                    <p class="pb-4 w-[85%]">
                        Still risk assessed, these applications have an agreement
                        in place for schools to receive better value. Schools will
                        need to fund purchases
                    </p>
                </div>
                <div class="flex justify-center flex-col h-1/4 my-auto" />
            </div>
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