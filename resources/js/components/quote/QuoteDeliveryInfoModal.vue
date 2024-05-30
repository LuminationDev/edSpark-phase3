<script setup>
import {useVuelidate} from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {computed, reactive, ref} from 'vue'

import TextInput from "@/js/components/bases/TextInput.vue";
import WarningModal from "@/js/components/dma/WarningModal.vue";

const props = defineProps({})

const emits = defineEmits([])


const state = reactive({
    name: 'test',
    institution: '',
    address: ''
})

const rules = {
    name: required,
    institution: required,
    address: required
}


const v$ = useVuelidate(rules, state)

const handleModalCancel = () =>{

}

const handleModalConfirm = () =>{
    console.log('confirmed')
    v$.value.$validate()
}

</script>

<template>
    <div
        class="absolute top-0 right-0 bottom-0 left-0 globalSearchScreenContainer grid place-items-center h-screen w-screen z-50"
    >
        <div
            class="bg-main-darkTeal/80 fixed top-0 left-0 grayoverlay h-[1000vh] w-full z-40"
        />
        <div
            class="
                -translate-x-1/2
                -translate-y-1/2
                bg-white
                border-[1px]
                border-slate-300
                fixed
                top-1/2
                left-1/2
                p-6
                rounded-xl
                shadow
                w-[50vh]
                z-50
                "
        >
            <div class="mb-2 text-lg text-main-darkTeal">
                Delivery Info
            </div>
            <div class="mb-2 text-base">
                Before proceeding with the quote, please ensure this information is correct
            </div>
            <div class="border-[1px] border-slate-300 flex flex-col innerContainer px-4 py-2 rounded-xl shadow">
                <TextInput
                    v-model="v$.name.$model"
                    class=""
                    field-id="deliveryName"
                    :v$="v$.name"
                    placeholder="Name"
                >
                    <template #label>
                        Full name
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.institution.$model"
                    field-id="deliveryInstitution"
                    :v$="v$.institution"
                    placeholder="Institution"
                >
                    <template #label>
                        Institution Name
                    </template>
                </TextInput>
                <TextInput
                    v-model="v$.address.$model"
                    field-id="deliveryName"
                    :v$="v$.address"
                    placeholder="Address"
                >
                    <template #label>
                        Address
                    </template>
                </TextInput>
            </div>
        </div>
    </div>
</template>
