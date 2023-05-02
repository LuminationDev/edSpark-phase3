<script setup>
    import AdviceCard from '../advice/AdviceCard.vue';

    const props = defineProps({
        advice: {
            type: Array,
            required: true
        }
    });
</script>

<template>
    <div class="py-20 px-20">
        <div class="grid grid-cols-3 gap-[24px] w-full h-full">
            <div class="col-span-1">
                <div class="grid grid-cols-3 row-span-4 py-4">
                    <div class="col-span-1 row-span-1">
                        <img
                            class=""
                            src="../../../assets/images/WhatIsDag.png"
                            alt="Digital Adoption Group Icon"
                        >
                    </div>
                    <div class="col-span-2 row-span-1 flex place-items-center">
                        <h4 class="text-[24px] font-semibold">
                            What is the D.A.G?
                        </h4>
                    </div>
                    <div class="col-span-3 row-span-3 py-8">
                        <p>
                            The Digital Adoption Group (DAG) is a cross-divisional
                            group that provides holistic and focused advice on
                            digital technologies
                        </p>
                        <p class="mt-8">
                            The objective of the DAG is to support leaders and
                            educators to integrate high-impact technologies by
                            providing system-wide and practical advice on what
                            technology to purchase for teaching and learning
                            and for what purpose.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-span-2">
                <div class="flex flex-row flex-1 justify-end flex-wrap gap-[24px] w-full h-full">
                    <AdviceCard
                        v-for="(advice, index) in advice.slice(0,2)"
                        :key="index"
                        :advice-content="advice"
                        :show-icon="true"
                        :numberPerRow="2"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
