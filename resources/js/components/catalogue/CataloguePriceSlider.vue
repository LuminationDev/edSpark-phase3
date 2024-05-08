<script setup lang="ts">
import {computed, ref} from "vue";

import VueNoUiSlider from "@/js/components/slider/VueNoUiSlider.vue";

const priceSliderValues = [0, 25, 50, 100, 250, 400, 500, 1000, 1500, 2000, 4000, 8000, 10000];
const currentPriceValues = ref([0, 10000])
const format = {
    to: function (value) {
        return priceSliderValues[Math.round(value)];
    },
    from: function (value) {
        return priceSliderValues.indexOf(Number(value));
    }
};
const priceSliderConfig = {
    start: [0, 10000],
    range: {min: 0, max: priceSliderValues.length - 1},
    connect: [false, true, false],
    tooltips: false,
    step: 1,
    format: format,
}

const priceRange =  defineModel('priceRange')

const handleNewValuesFromSlider = (values) => {
    currentPriceValues.value = values
    priceRange.value = values
}

const minActivePrice = computed(() => {
    return currentPriceValues.value[0]
})

const maxActivePrice = computed(() => {
    return currentPriceValues.value[1]
})
</script>

<template>
    <div class="mt-8 priceRangeHeader text-lg">
        Price Range
    </div>
    <VueNoUiSlider
        :values="priceSliderValues"
        :config="priceSliderConfig"
        @update:values="handleNewValuesFromSlider"
    />
    <div class="flex justify-between flex-row minMaxPriceDisplay mt-2">
        <div
            class="
                border-[1px]
                border-slate-300
                currencyInput
                flex
                justify-center
                items-center
                flex-row
                inputWrapper
                pl-2
                rounded-lg
                shadow
                "
        >
            <span class="text-lg">$</span>
            <input
                id="minPrice"
                type="text"
                class="border-0 font-normal ml-1 p-0 py-2 rounded-lg text-lg w-14 focus:ring-0"
                disabled
                :value="minActivePrice"
            >
        </div>
        <div class="currencyInput flex flex-row inputWrapper">
            <div
                class="
                    border-[1px]
                    border-slate-300
                    currencyInput
                    flex
                    justify-center
                    items-center
                    flex-row
                    inputWrapper
                    pl-2
                    rounded-lg
                    shadow
                    "
            >
                <span class="text-lg">$</span>
                <input
                    id="maxPrice"
                    type="text"
                    class="border-0 font-normal ml-1 p-0 py-2 rounded-lg text-lg w-16  focus:ring-0"
                    disabled
                    :value="maxActivePrice"
                >
            </div>
        </div>
    </div>
</template>


