<script setup>
import {computed} from "vue";

const props = defineProps({
    itemArray: {
        type: Array,
        required: true

    }
})

const dateItemContent = computed(() => {
    if (typeof props.itemArray == "object") {
        return Object.values(props.itemArray)
    } else {
        return props.itemArray
    }
})

</script>
<template>
    <div class="extraContent relative">
        <div
            class="absolute top-[18%] left-[16.5%] bg-black connectingLine h-[69%] hidden w-1 z-10 md:!block"
        />
        <div
            v-for="(item,index) in dateItemContent"
            :key="index"
            class="eachContent py-2"
        >
            <div class="flex flex-row w-full">
                <div class="extraContentIcon hidden items-center justify-center relative w-1/3 md:!flex">
                    <div
                        class="
                            absolute
                            bg-white
                            border-4
                            border-black
                            font-bold
                            h-24
                            hidden
                            numberListcontainer
                            p-4
                            rounded-[50%]
                            text-2xl
                            w-24
                            z-20
                            items-center
                            justify-center
                            md:!flex
                            "
                    >
                        {{ index + 1 }}
                        <!-- {{ dateTime(item.start_date) }} -->
                    </div>
                </div>
                <div
                    class="flex flex-col w-2/3"
                >
                    <div class="font-semibold heading mb-2 mt-6 text-xl">
                        {{ `${index + 1}. ` + item.heading }}
                    </div>

                    <div v-html="item.content" />
                </div>
            </div>
        </div>
    </div>
</template>
