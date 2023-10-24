<script setup>
import whatIsDag from '@/assets/images/WhatIsDag.png'
</script>

<template>
    <div class="grid gap-6 h-full px-4 w-full">
        <div class="grid grid-cols-3 py-4 row-span-4">
            <div class="col-span-1 row-span-1">
                <img
                    class=""
                    :src="whatIsDag"
                    alt="Digital Adoption Group Icon"
                >
            </div>
            <div class="col-span-2 flex ml-2 place-items-center row-span-1">
                <h4 class="font-semibold text-[24px]">
                    What is the DAG?
                </h4>
            </div>
            <div class="col-span-3 py-8 row-span-3">
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
</template>
