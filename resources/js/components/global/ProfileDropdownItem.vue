<script setup>

const props = defineProps({
    isRouterLink :{
        type: Boolean,
        required: true
    },
    targetPath :{
        type: String,
        required: false,
        default: '/'
    },
    clickCallback:{
        type: Function,
        required: false,
        default: () =>{}
    }

})

</script>

<template>
    <router-link
        v-if="props.isRouterLink"
        :to="props.targetPath"
    >
        <button
            class="
                flex
                justify-start
                flex-row
                font-medium
                place-items-center
                px-2
                py-3
                text-[18px]
                text-white
                w-full
                gap-4
                hover:bg-[#405974]
                "
        >
            <slot />
        </button>
    </router-link>
    <button
        v-else
        class="flex justify-start flex-row font-medium gap-4 place-items-center px-2 py-3 text-[18px] text-white w-full hover:bg-[#405974]"
        @click="props.clickCallback"
    >
        <slot />
    </button>
</template>
