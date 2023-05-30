<script setup>
const props = defineProps({
    itemArray: {
        type: Array,
        required: true

    }
})
</script>
<template>
    <div class="extraContent relative ">
        <div
            class="connectingLine absolute w-1 h-[69%] bg-black z-10 top-[14%] left-[12.4%]"
        />
        <div
            v-for="(item,index) in props.itemArray"
            :key="index"
            class="eachContent py-2"
        >
            <div class="flex flex-row w-full">
                <div class="extraContentIcon w-1/4 flex justify-center items-center relative">
                    <div class="numberListcontainer font-bold text-2xl border-4 bg-white border-black p-4 w-24 h-24 rounded-[50%] flex justify-center items-center absolute z-20">
                        {{ index + 1 }}
                    </div>
                </div>
                <div
                    class="w-3/4 flex flex-col"
                >
                    <div class="heading font-semibold mt-6 text-xl mb-2">
                        <span class="font-bold text-2xl">{{ `${index + 1}. ` }}</span>
                        {{ item.heading }}
                    </div>

                    <div v-html="item.content" />
                </div>
            </div>
        </div>
    </div>
</template>
