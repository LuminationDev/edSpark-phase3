<script setup lang="ts">





const props = defineProps({
    leftHeading:{
        type: String,
        required: true
    },
    leftDescription:{
        type: String,
        required: true
    }
})

</script>



<template>
    <div class="!min-w-[400px] flex xl:flex-row flex-col gap-6 xl:gap-1 m-6 xl:m-6">
        <div class="leftLayout p-2 xl:w-[30%]">
            <div class="font-semibold text-lg xl:!text-md">
                {{ leftHeading }}
            </div>
            <div class="text-md xl:!text-sm">
                {{ leftDescription }}
            </div>
        </div>
        <div class="border-2 border-gray-200 h-full p-4 rightLayout rounded-xl xl:w-[70%]">
            <div><slot name="content" /></div>
            <div class="border-[1px] border-gray-200 mt-10" />
            <div class="flex flex-row gap-6 mb-2 ml-2 mr-2 mt-4">
                <slot name="buttons" />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
