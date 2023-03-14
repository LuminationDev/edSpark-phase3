<script>
    /**
     * Import Dependencies
     */
    import { ref } from 'vue';

    /**
     * Import Stores
     */
    import { useUserStore } from '../stores/useUserStore';

    /**
     * Import Components
     */
    import Edit from '../components/svg/Edit.vue';

    /**
     * SVG's
     */
    import Save from '../components/svg/Save.vue';
    import Close from '../components/svg/Close.vue';

    export default {
        setup() {
            const userStore = useUserStore();

            const isEditAvatar = ref(false);

            const updatedName = ref('');


            const handleSaveChange = () => {
                // userStore.updateUser(updatedName.value);
            };

            return {
                userStore,
                isEditAvatar,
                handleSaveChange,
                updatedName,
            };
        },

        components: {
            Edit,
            Save,
            Close,
        },

        data() {
            return {
                user: this.userStore.getUser,
                editingField: false,
            }
        },

        methods: {
            editField() {
                this.editingField = true;
                // TODO: make this not rely on a timeout for component to render
                setTimeout(() => {
                    this.$refs.editName.focus();
                }, 100);
            },

            handleCancelEdit() {
                this.editingField = false;
            },
        }

    }
</script>

<template>
    <div class="h-screen w-full bg-white">
        <div class="w-full h-full grid grid-cols-12 grid-rows-6">

            <div class="col-span-12 row-span-2 grid grid-cols-6 justify-center mt-[100px] px-[81px]">
                <div @mouseenter="isEditAvatar = !isEditAvatar" @mouseleave="isEditAvatar = !isEditAvatar" class="col-span-1 cursor-pointer h-[200px] w-[200px] bg-orange-500 rounded-full flex justify-center place-items-center relative">
                    <h1 class="text-[72px] text-white font-bold">
                        {{ user.display_name }}
                    </h1>
                    <div class="absolute w-[48px] h-[48px] rounded-full bg-white flex justify-center place-items-center top-0 right-[24px] border border-black border-2" v-if="isEditAvatar">
                        <Edit class="w-[24px] h-24px" />
                    </div>

                </div>

                <div class="col-span-5 grid grid-rows-4 grid-cols-1">
                    <!-- Name -->
                    <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center" ref="editingParent">
                        <p ref="beforeRenderInput" class="text-[24px] font-bold" for="name">Name:</p>

                        <input v-if="this.editingField" v-model="this.updatedName" ref="editName" class="!w-[320px]" type="text" :placeholder="user.full_name">

                        <div class="group" v-if="!this.editingField">
                            <button class="flex flex-row gap-4 place-items-center" @click.prevent="editField">
                                <p class="text-[24px] font-normal group-hover:underline" v-if="!this.editingField">{{ user.full_name }}</p>
                                <Edit class="h-[18px] group-hover:scale-110" v-if="!this.editingField" />

                            </button>
                        </div>

                        <button class="w-fit p-2 hover:bg-gray-200" @click.prevent="handleSaveChange">
                            <Save class="h-[24px] w-[24px]"  v-if="this.editingField"/>
                        </button>

                        <button class="w-fit p-2 hover:bg-gray-200" @click.prevent="handleCancelEdit">
                            <Close class="h-[24px] w-[24px]" v-if="this.editingField"/>
                        </button>

                    </div>

                    <!-- Email -->
                    <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">
                        <p class="text-[24px] font-bold" for="name">Email:</p>

                        <div class="group">
                            <button class="flex flex-row gap-4 place-items-center">
                                <p class="text-[24px] font-normal group-hover:underline">{{ user.email }}</p>
                                <!-- <Edit class="h-[18px] group-hover:scale-110" /> -->
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-span-12 row-span-4 ">
                <nav class="bg-[#1C5CA9]">
                    <ul class="flex flex-row gap-4 mt-[100px] px-[81px] text-white text-[18px] font-bold">
                        <li class="my-2">
                            <button class="py-2 px-4">Bio</button>
                        </li>
                        <li class="my-2">
                            <button class="py-2 px-4">Interests</button>
                        </li>
                        <li class="my-2">
                            <button class="py-2 px-4">Work</button>
                        </li>
                    </ul>
                </nav>

                <div class="mt-[100px] px-[81px]">

                </div>
            </div>

        </div>
    </div>
</template>
