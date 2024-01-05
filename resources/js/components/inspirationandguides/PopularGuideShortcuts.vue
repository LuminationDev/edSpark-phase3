<script setup>
import {computed,ref} from 'vue'

const props = defineProps({
    adviceList: {
        type: Array,
        required: true
    }
})

const handleClickPopularGuide = () =>{
    console.log('clciked on one please router me away')

}
const filterGuide = computed(() =>{
    console.log(props.adviceList.slice(0,6))
    return props.adviceList.slice(0,6)
})


</script>

<template>
    <div class="grid grid-cols-2 gap-8 w-full">
        <div
            v-for="(guide,index) in filterGuide"
            :key="index"
            class="
                border-2
                border-main-teal
                cursor-pointer
                popularGuideItem
                px-8
                py-4
                rounded
                text-2xl
                hover:text-white
                w-full
                hover:bg-main-teal
                "
        >
            <div class="flex justify-between flex-row w-full">
                <div class="title">
                    {{ guide.title }}
                </div>
                <div class="Arrow">
                    ->
                </div>
            </div>
        </div>
    </div>
</template>

