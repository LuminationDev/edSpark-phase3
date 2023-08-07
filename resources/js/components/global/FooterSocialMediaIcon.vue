<script setup>
const props = defineProps({
    fillColor:{
        type: String,
        required: true,
    },
    targetUrl:{
        type: String,
        required: true
    },
    iconName:{
        type: String,
        required: false,
        default: 'Social Media Icon'
    }
})

const handleClickSocialMediaIcon = () =>{
    window.location = props.targetUrl
}
</script>

<template>
    <div
        class="border-[1px] border-white grid place-items-center h-10 p-2 rounded-lg w-10 hover:bg-white hover:cursor-pointer"
        :class="`fill-${props.fillColor}`"
        :title="props.iconName"
        @click="handleClickSocialMediaIcon"
    >
        <slot name="iconSVG" />
    </div>
</template>