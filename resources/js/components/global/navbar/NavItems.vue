<script setup>
import {onMounted, ref} from 'vue';

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
            py-4
            relative
            transition-all
            lg:!py-0
            "
        @click="props.clickCallback"
    >
        <router-link
            :to="{ name: route.name }"
            class=""
        >
            {{ route.meta.customText ?? route.name }}
        </router-link>
        <div
            v-if="hasChildren && !isMobile"
            class="absolute bottom-0 decoration-4 h-full overlayForTriggerDropdown w-full"
            @mouseover="navDropdownToggle = true"
            @mouseleave="navDropdownToggle = false"
        >
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
                        class="first-letter:uppercase text-sm  hover:bg-[#E7ECEE] hover:font-bold"
                        :to="{ name: child.name }"
                        :route="child"
                    />
                </div>
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
            class="flex p-4"
        >
            {{ route.meta.customText ?? route.name }}
        </router-link>
    </li>
</template>
