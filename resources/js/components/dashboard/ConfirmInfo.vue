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

        methods: {
            submitStep() {
                console.log('Next step')
                this.activeStepIndex++;

                this.$emit('handleStep', this.activeStepIndex)

                // if(!this.steps[this.activeStepIndex].step_valid){
                //     this.$emit('valdiateStep', this.stepIndex);
                //     return false;
                // } else {

                // }

                // console.log('step length', this.steps.length);
                // console.log('active step', this.activeStepIndex);
                console.log('Stop when true', this.activeStepIndex === this.steps.length)

                if(this.activeStepIndex === this.steps.length){
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
                
            }
        }
    }
</script>

<template>
    <div class="bg-white p-[48px] w-[500px] flex flex-col absolute z-50 shadow-lg -top-[500px] left-1/2 -translate-x-1/2">
        <slot name="formHeader"></slot>

        <form @submit.prevent="submitStep">
            <div
                v-for="(step, index) in steps"
                :key="index"
                :id="`step${index+1}`"
                v-show="activeStepIndex === index"
                class="pt-6 flex flex-col gap-6"
            >
                <slot :name="`step${index+1}`"></slot>
            </div>

            <div v-if="this.isFinished" class="pt-6 flex flex-col gap-6">
                Complete!

                <button @click="closePopup" class="!bg-[#002856] ml-auto mt-auto px-4 py-2 text-white w-fit">
                    Close
                </button>
            </div>

            <div class="mt-6 flex flex-row justify-between w-full">
                <div v-if="this.activeStepIndex > 0 && !this.isFinished">
                    <button ref="backBtn" @click.prevent="stepBack">
                        Back
                    </button>
                </div>
                <slot name="formFooter" v-if="!this.isFinished">
                    <!-- <button class="bg-[#002856] ml-auto mt-6 px-4 py-2 text-white w-fit" type="submit">Submit</button> -->
                </slot>
            </div>

        </form>
    </div>
</template>
