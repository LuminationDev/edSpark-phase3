<script setup>

const props = defineProps({
    menuArray:{
        type: Array,
        required: true
    },
    emitToBase:{
        type: Function,
        required: true
    },
    activeSubpage:{
        type: String,
        required: true
    }
})

</script>

<template>
    <div class="flex flex-row overflow-x-scroll md:!overflow-x-hidden">
        <button
            v-for="(menu, index) in menuArray"
            :key="index"
            class="font-semibold px-4 py-3 md:!px-6"
            :class="{'!text-black h-full rounded-t-md bg-white ' : menu['value'] === activeSubpage}"
            @click="() => emitToBase(menu.value)"
        >
            {{ menu['displayText'] }}
        </button>
    </div>
</template>
