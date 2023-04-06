<script>
    /**
     * Import SVG's
     */
import Close from '../../svg/Close.vue';

export default {

    components: {
        Close
    },
    props: {
        mapPopupName: String,
        mapPopupIndex: String
    },

    mounted() {
        console.log(this.mapPopupIndex)
    },

    methods: {
        handleClosePopup() {
            this.$emit('handleToggle')
        },

        handleEmit() {
            this.$emit('handleLinkToSchool')
        }
    },


}
</script>

<template>
    <div

        ref="mapPopup"

        class="absolute p-4 bg-white shadow-xl w-[340px] flex flex-col gap-6"
    >
        <div class="relative flex flex-row justify-between place-items-center">
            <h3 class="text-[18px] font-medium">
                {{ mapPopupName }}
            </h3>
            <div class="">
                <button class="hover:bg-gray-100 p-2">
                    <Close
                        class="w-[18px] h-[18px]"
                        @click="handleClosePopup"
                    />
                </button>
            </div>
        </div>
        <div class="flex flex-row justify-end">
            <button
                class="bg-[#0072DA] text-white px-8 py-3 hover:bg-[#0359a9]"
                @click="handleEmit"
            >
                Visit
            </button>
        </div>
    </div>
</template>
