<script setup>
console.log('aseSingleSubmenu')

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
    <div
        v-for="(menu, index) in menuArray"
        :key="index"
        class="py-2 px-6"
        :class="{'!text-black h-full rounded-t-md bg-white ' : menu['value'] === activeSubpage}"
        @click="() => emitToBase(menu.value)"
    >
        {{ menu['displayText'] }}
    </div>
</template>