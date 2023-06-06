<script>
export default {
    props: {
        steps: Array
    },

    data() {
        return {
            activeStepIndex: 0,
            canStepBack: false,
            isFinished: false
        }
    },

    mounted() {
        this.handleKeepPopupCenter();
    },

    methods: {
        submitStep() {
            console.log('Next step')
            this.activeStepIndex++;

            this.$emit('handleStep', this.activeStepIndex);
            console.log('Stop when true', this.activeStepIndex === this.steps.length)

            if (this.activeStepIndex === this.steps.length) {
                this.$emit('onComplete');
                this.isFinished = true;
                return false;
            }

            // while(this.steps[this.activeStepIndex].step_skip === true){
            //     this.activeStepIndex++;
            // }

            if (this.activeStepIndex > 0) {
                console.log(this.canStepBack)
                this.canStepBack = true;
            }

            console.log(this.activeStepIndex);
        },

        stepBack() {
            console.log('Clicked, go back 1', this.activeStepIndex);
            this.activeStepIndex--;
            this.$emit('handleStep', this.activeStepIndex)
        },

        closePopup() {
            this.$emit('closePopup');

        },

        handleKeepPopupCenter() {
            // const popup = this.$refs.newVisitorForm;
            // const windowHeight = window.innerHeight;
            // const windowWidth = window.innerWidth;
            // const popupWidth = popup.offsetWidth;
            // const popupHeight = popup.offsetHeight;

            // console.log(popup);
            // console.log(popupWidth);
            // console.log(windowWidth - popupWidth);

            // const topOffset = (windowHeight - popupHeight) / 2;

            // popup.style.left = `${(windowWidth - popupWidth) / 2}px`;
            // popup.style.top = `${topOffset - 540}px`;
        }
    }
}
</script>

<template>
    <div
        ref="newVisitorForm"
        class="bg-white p-[48px] w-[500px] flex flex-col absolute z-50 shadow-lg -top-[700px] left-1/2 -translate-x-1/2"
    >
        <slot name="formHeader" />

        <form
            autocomplete="off"
            @submit.prevent="submitStep"
        >
            <div
                v-for="(step, index) in steps"
                v-show="activeStepIndex === index"
                :id="`step${index+1}`"
                :key="index"
                class="pt-6 flex flex-col gap-6"
            >
                <slot :name="`step${index+1}`" />
            </div>

            <div
                v-if="isFinished"
                class="pt-6 flex flex-col gap-6"
            >
                Complete!

                <button
                    class="!bg-[#002856] ml-auto mt-auto px-4 py-2 text-white w-fit"
                    @click="closePopup"
                >
                    Close
                </button>
            </div>

            <div class="mt-6 flex flex-row justify-between w-full">
                <div v-if="activeStepIndex > 0 && !isFinished">
                    <button
                        ref="backBtn"
                        @click.prevent="stepBack"
                    >
                        Back
                    </button>
                </div>
                <slot
                    v-if="!isFinished"
                    name="formFooter"
                >
                    <!-- <button class="bg-[#002856] ml-auto mt-6 px-4 py-2 text-white w-fit" type="submit">Submit</button> -->
                </slot>
            </div>
        </form>
    </div>
</template>
