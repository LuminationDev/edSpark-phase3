<script setup>
import {ref,computed} from 'vue'
import GenericButton from "@/js/components/button/GenericButton.vue";

const modalData = ref({})
/**
 *  will make modal content extensible and reusable
 *  modalContent to be formed = [
 *  {
 *      type: text,
 *      content: 'Welcome to school page'
 *  },
 *  {
 *      type: input,
 *      name: principalEmail --> IMPORTANT: Will be used as the KEY to store values inside modalData
 *      placeholder: 'insert principals email address',
 *      content: "please nominate your principal to notify him/her to establish school page"
 *  },
 *  {
 *      type: anytother type
 *  }
 * ]
 */
const props = defineProps({
    modalContent:{
        type: Array,
        required: true
    },
    currentIndex:{
        type: Number,
        required: true
    },
    nextCallback:{
        type: Function,
        required: true,
    },
    prevCallback:{
        type: Function,
        required: false,
        default: () => console.log('handle prev click')
    },
    saveCallback:{
        type: Function,
        required: false,
        default: () => console.log('save is handled')
    }
})

const emits = defineEmits(['sendModalData','sendClickOutsidePopup','sendSavePopup'])

const handleModalData = (event,name) => {
    modalData.value[name] = event.target.value
}

const handleClickOutsidePopup = () => {
    // emit can be captured directly by grandparents
    // in the case of SchoolWelcomePopup, its captured directly inside Schools.vue
    // with this way, any parent component can just capture this emit and perform the required ops
    emits('sendClickOutsidePopup')
}
const handleSaveModal = () => {
    // emitted in the final page when the last button closes the modal
    emits('sendSavePopup', modalData.value)
}

const handleModalInput = (name,input) => {
    // modalData.value[name] = input
    console.log(modalData.value)
}

const currentContent = computed( () =>{
    return props.modalContent[props.currentIndex]
})

</script>
<template>
    <div class="absolute grid place-content-center top-0 bottom-0 left-0 right-0 !mt-0">
        <div
            class="grey-overlay sticky w-screen h-screen bg-gray-400 z-30 opacity-60 top-0 left-0 grid place-content-center top-0 bottom-0 left-0 right-0 mt-[-100%]"
            @click="handleClickOutsidePopup"
        />
        <div
            class="popup-content sticky z-[60] w-[50vw] h-[50vh] max-w-[700px] max-h-[600px] -translate-y-[80%] px-10 py-4 text-center bg-white rounded-3xl flex flex-col  absolute m-auto top-0 bottom-0 left-0 right-0"
        >
            <div class="flex flex-col h-full">
                <div class="text-2xl font-semibold mt-4 mb-6">
                    {{ currentContent.title }}
                </div>
                <div
                    v-if="currentContent.type === 'text'"
                    class="mb-6"
                >
                    {{ currentContent.content }}
                </div>
                <div v-if="currentContent.type === 'input'">
                    <div class="mb-2">
                        {{ currentContent.content }}
                    </div>
                    <div class="text-sm">
                        Email address
                    </div>
                    <input
                        v-model="modalData[currentContent.name]"
                        type="text"
                        class="border-2 rounded-lg px-4 py-2 border-black w-3/4"
                        @input="handleModalInput"
                    >
                </div>

                <div class="flex flex-row justify-around w-full mt-auto mb-6">
                    <GenericButton
                        v-if="currentIndex > 0"
                        class="px-8 py-2"
                        :callback="props.prevCallback"
                    >
                        {{ `<- prev` }}
                    </GenericButton>
                    <GenericButton
                        v-if="currentIndex < modalContent.length - 1"
                        class="px-8 py-2"
                        :callback="props.nextCallback"
                    >
                        {{ `next ->` }}
                    </GenericButton>
                    <GenericButton
                        v-if="currentIndex === modalContent.length - 1"
                        class="px-8 py-2"
                        :callback="handleSaveModal"
                    >
                        {{ `Save and close` }}
                    </GenericButton>
                </div>
            </div>
        </div>
    </div>
</template>