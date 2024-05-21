<script setup>
import {ref} from 'vue'

import ChevronDownIcon from "@/js/components/svg/chevron/ChevronDownIcon.vue";

defineProps({
    title: String,
    titleClasses: String,
})

const isOpen = ref(true)

const toggle = () => {
    isOpen.value = !isOpen.value
}

const beforeEnter = (el) => {
    el.style.height = '0'
}

const enter = (el) => {
    el.style.height = el.scrollHeight + 'px'
}

const leave = (el) => {
    el.style.height = '0'
}
</script>

<template>
    <div>
        <div
            class="accordion-header cursor-pointer flex flex-row mb-4 text-main-darkTeal text-xl"
            @click="toggle"
        >
            <h2 :class="titleClasses">
                {{ title }}
            </h2>
            <ChevronDownIcon
                class="h-6 ml-1 mt-0.5 w-6"
                :class="{'rotate-180' :isOpen}"
            />
        </div>
        <transition
            name="accordion"
            @before-enter="beforeEnter"
            @enter="enter"
            @leave="leave"
        >
            <div
                v-show="isOpen"
                class="accordion-content"
            >
                <slot />
            </div>
        </transition>
    </div>
</template>


<style scoped>
.accordion-header {
    @apply cursor-pointer px-4 py-2 ;
}

.accordion-content {
    @apply overflow-hidden;
}

.accordion-enter-active,
.accordion-leave-active {
    transition: height 0.5s ease;
}

.accordion-enter,
.accordion-leave-to /* .accordion-leave-active in <2.1.8 */
{
    height: 0;
}
</style>
