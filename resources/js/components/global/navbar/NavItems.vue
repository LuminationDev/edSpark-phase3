<script setup>
import {onMounted, ref} from 'vue';
import {useRouter} from "vue-router";

const props = defineProps({
    route: {
        type: Object,
        required: true
    },
    clickCallback: {
        type: Function,
        required: false,
        default: () => {
        }
    }
});

const hasChildren = ref(false)
if (props.route.children && props.route.children.length > 0) {
    hasChildren.value = true
}
const navDropdownToggle = ref(false)

const isMobile = (window.innerWidth <= 1024)

const router = useRouter()

const handleClickParentNavItems = () => {
    console.log('im called hehe')
    return router.push({name: props.route.name})
}
</script>

<template>
    <li
        v-if="hasChildren"
        class="
            cursor-pointer
            decoration-4
            decoration-[#B8E2DC]
            first-letter:uppercase
            flex
            items-center
            h-full
            ml-0
            pr-2
            py-4
            relative
            transition-all
            lg:!py-0
            "
        @mouseleave="navDropdownToggle = false"
    >
        <router-link
            :to="{ name: route.name }"
            class="flex items-center h-full text-center"
            @mouseover="navDropdownToggle = true"
        >
            {{ route.meta.customText ?? route.name }}
        </router-link>
        <div
            v-show="navDropdownToggle"
            class="absolute top-16 dropdownBackgroundContainer"
        >
            <div
                class="-ml-2 bg-[#F5F5F5] font-medium h-full p-0 w-60"
            >
                <NavItems
                    v-for="(child, index) in props.route.children"
                    :key="index"
                    class="first-letter:uppercase text-base text-main-darkGrey hover:bg-[#E7ECEE] hover:font-bold"
                    :to="{ name: child.name }"
                    :route="child"
                />
            </div>
        </div>
    </li>
    <li
        v-else
        class=""
        @click="props.clickCallback"
    >
        <router-link
            :to="{ name: route.name }"
            class="flex px-2 py-4"
        >
            {{ route.meta.customText ?? route.name }}
        </router-link>
    </li>
</template>
