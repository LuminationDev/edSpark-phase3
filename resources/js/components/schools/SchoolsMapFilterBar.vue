<script>
    export default {
        props: {
            showFilters: Boolean
        },

        data() {
            return {
                schoolSearch: ''
            }
        },

        mounted() {
            console.log(this.showFilters);
        }
    }
</script>

<template>
    <div class="absolute p-6 transition-all top-0 bottom-0 w-full bg-[#0072DA] z-40" :class="showFilters ? 'h-[350px]' : '!h-0 opacity-0' ">
        <div class="mt-12 flex flex-col flex-wrap">
            <div class="flex-1 w-[50%]">
                <h5 class="font-normal text-[18px] text-white mb-4">Search by school name</h5>
                <input type="text" placeholder="Search for a school" v-model="mapInput" @input="$emit('update:modelValue', $event.target.value)">
            </div>
        </div>

    </div>
</template>
