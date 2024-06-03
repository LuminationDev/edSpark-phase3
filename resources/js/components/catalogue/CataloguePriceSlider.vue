<script setup lang="ts">
import {debounce} from "lodash";
import {computed, ref, watch} from "vue";

import VueNoUiSlider from "@/js/components/slider/VueNoUiSlider.vue";

const priceSliderValues = [0, 25, 50, 100, 250, 400, 500, 1000, 1500, 2000, 4000, 8000, 10000, 15000, 20000, 30000];
const currentPriceValues = ref([0, 30000])
const format = {
    to: function (value) {
        return priceSliderValues[Math.round(value)];
    },
    from: function (value) {
        return priceSliderValues.indexOf(Number(value));
    }
};
const priceSliderConfig = {
    start: [0, 30000],
    range: {min: 0, max: priceSliderValues.length - 1},
    connect: [false, true, false],
    tooltips: false,
    step: 1,
    format: format,
}
const emits = defineEmits(['priceChanged'])
const priceRange = defineModel('priceRange')
const sliderRefreshKey = 0

const handleNewValuesFromSlider = debounce((values) => {
    currentPriceValues.value = values
    priceRange.value = values
}, 500)

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
        :key="sliderRefreshKey"
        :values="priceSliderValues"
        :config="priceSliderConfig"
        @update:values="handleNewValuesFromSlider"
    />
    <div class="flex justify-between flex-row mb-4 minMaxPriceDisplay mt-2">
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


