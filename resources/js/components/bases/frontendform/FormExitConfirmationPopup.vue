<script setup>

import GenericButton from "@/js/components/button/GenericButton.vue";

const props = defineProps({
    overlayCallback: {
        type: Function,
        required: true
    },
    stayCallback : {
        type: Function,
        required: true
    },
    leaveCallback : {
        type: Function,
        required: true
    }
})

</script>

<template>
    <div
        class="absolute top-0 right-0 bottom-0 left-0 globalSearchScreenContainer grid place-items-center h-screen w-screen z-50"
    >
        <div
            class="bg-main-navy/80 fixed top-0 left-0 grayoverlay h-[1000vh] w-full z-40"
            @click="props.overlayCallback"
        />
        <div class="bg-white drop-shadow-xl flex flex-col overflow-y-auto p-10 relative rounded text-black z-50">
            <div class="mb-8 popup textContent">
                You have unsaved changes, are you sure you want to leave?
            </div>
            <div class="flex justify-center items-center flex-row gap-6">
                <GenericButton
                    id="formConfirmationLeaveButton"
                    :callback="leaveCallback"
                    type="teal"
                    class="hover:!bg-secondary-cherry"
                >
                    Leave
                </GenericButton>
                <GenericButton
                    id="formConfirmationStayButton"
                    :callback="stayCallback"
                    type="teal"
                >
                    Stay
                </GenericButton>
            </div>
        </div>
    </div>
</template>
