<script>
    import { ref, computed } from 'vue';

    export default {

        setup() {
            const showFilters = ref(false);
            const toggleFilters = computed(() => {
                console.log('toggle');
                showFilters.value = !showFilters.value;
            });

            return {
                toggleFilters,
                showFilters
            }
        },

        watch: {
            showFilters: {
                handler(newVal, oldVal) {

                }
            }
        },

        data() {
            return {
                center: { lat: -34.9285, lng: 138.6007 },
                zoom: 12
            }
        },
    }
</script>

<template>
    <div class="w-full relative border border-[#0072DA]">
        <div class="bg-[#0072DA] h-[72px] flex flex-row place-items-center justify-between px-[48px] relative z-50">
            <h1 class="text-[30px] font-bold text-white">Search for Schools</h1>

            <button class="bg-white text-[#0072DA] px-8 py-3" @click="this.showFilters = !this.showFilters">
                {{ (showFilters ? 'Close' : 'Filters') }}
            </button>
        </div>
        <div class="absolute transition-all top-0 bottom-0 w-full bg-[#0072DA] z-40" :class="showFilters ? 'h-[350px]' : '!h-0' ">

        </div>
        <div class="relative">
            <GMapMap :center="center" :zoom="zoom" map-type-id="terrain" style="width: 100%; height: 700px">

            </GMapMap>
        </div>

    </div>
</template>

<style>
    /* .vue-map {
        max-width: 100% !important;
    } */

    /* .vue-map-container {
        z-index: -10;
        position: relative;
    } */
    
</style>
