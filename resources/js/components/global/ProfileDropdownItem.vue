<script setup>
const props = defineProps({
    isRouterLink: {
        type: Boolean,
        required: true
    },
    targetPath: {
        type: String,
        required: false,
        default: '/'
    },
    clickCallback: {
        type: Function,
        required: false,
        default: () => {
        }
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
                font-light
                place-items-center
                px-2
                py-3
                text-[18px]
                hover:text-white
                w-full
                gap-4
                hover:bg-main-teal
                hover:rounded-lg
                hover:stroke-white
                "
        >
            <slot />
        </button>
    </router-link>
    <button
        v-else
        class="
            flex
            justify-start
            flex-row
            font-medium
            place-items-center
            px-2
            py-3
            text-[18px]
            hover:text-white
            w-full
            gap-4
            hover:bg-main-teal
            hover:rounded-lg
            hover:stroke-white
            "
        @click="props.clickCallback"
    >
        <slot />
    </button>
</template>
