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
    import UserInfoProfileFields from '../components/global/UserInfoProfileFields.vue';

    /**
     * SVG's
     */
    import Save from '../components/svg/Save.vue';
    import Close from '../components/svg/Close.vue';
    import Edit from '../components/svg/Edit.vue';

    export default {
        setup() {
            const userStore = useUserStore();

            const isEditAvatar = ref(false);

            const updatedName = ref('');

            const handleSaveChange = () => {
                console.log('Handle save values here');
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
            UserInfoProfileFields
        },

        data() {
            return {
                user: this.userStore.getUser,
                metadata: this.userStore.getUser.metadata,
                editingField: false,
                userInfoMenu: [],
                activeProfileField: 'Info',
                activeInfoItem: 'Biography',
                updatedYearLevel: 0,
                editIndex: null,
                subMenuItems: [
                    {
                        name: 'Info',
                        isActive: true
                    },
                    {
                        name: 'Work',
                        isActive: false
                    },
                    {
                        name: 'Messages',
                        isActive: false
                    }
                ]
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

            handleProfileSubMenuClick(subMenuItem) {
                this.subMenuItems.forEach(item => {
                    if (item.isActive) {
                        item.isActive = false;
                    };
                });

                console.log(subMenuItem);
                subMenuItem.isActive = true;
                this.activeProfileField = subMenuItem.name;
            },

            handleProfileInfoSelection(infoSection) {
                this.userInfoMenu.forEach(item => {
                    if (item.isActive) {
                        item.isActive = false;
                    };
                });

                console.log(infoSection);
                infoSection.isActive = true;
                this.activeInfoItem = infoSection.name;
            },

            // updateYearLevel(item, index) {

            //     this.updatedYearLevel = item;
            // },
        },

        mounted() {
            this.metadata.forEach(meta => {
                console.log(meta);
                switch (meta.user_meta_key) {
                    case 'biography':
                            this.userInfoMenu.push({
                                name: 'Biography',
                                isActive: true
                            })
                        break;
                    case 'yearLevels':
                            this.userInfoMenu.push({
                                name: 'Year levels',
                                isActive: false
                            });
                        break;

                    case 'subjects':
                            this.userInfoMenu.push({
                                name: 'Subjects',
                                isActive: false
                            });
                        break;

                    case 'interests':
                            this.userInfoMenu.push({
                                name: 'Interests',
                                isActive: false
                            });
                        break;

                    default:
                        break;
                }
            });


        }

    }
</script>

<template>
    <div class="h-full w-full bg-white">
        <div class="w-full flex flex-col">

            <div class="col-span-12 row-span-2 grid grid-cols-6 justify-center mt-[100px] px-[81px]">
                <div @mouseenter="isEditAvatar = !isEditAvatar" @mouseleave="isEditAvatar = !isEditAvatar" class="col-span-1 cursor-pointer h-[200px] w-[200px] bg-orange-500 rounded-full flex justify-center place-items-center relative">
                    <h1 class="text-[72px] text-white font-bold">
                        {{ user.display_name }}
                    </h1>
                    <div class="absolute w-[48px] h-[48px] rounded-full bg-white flex justify-center place-items-center top-0 right-[24px] border border-black border-2" v-if="isEditAvatar">
                        <Edit class="w-[24px] h-24px" />
                    </div>

                </div>

                <!-- User information component -->
                <!-- Use slots -->

                <div class="col-span-5 grid grid-rows-4 grid-cols-1">
                    <div>
                        <UserInfoProfileFields>
                            <template #updateField>
                                <input v-if="this.editingField" v-model="this.updatedName" ref="editName" class="!w-[320px]" type="text" :placeholder="user.full_name">
                            </template>

                            <template #fieldName>
                                <p ref="beforeRenderInput" class="text-[24px] font-bold" for="name">Name:</p>
                            </template>

                            <template #currentDetails>
                                <div class="group" v-if="!this.editingField">
                                    <button class="flex flex-row gap-4 place-items-center" @click.prevent="editField">
                                        <p class="text-[24px] font-normal group-hover:underline" v-if="!this.editingField">{{ user.full_name }}</p>
                                        <Edit class="h-[18px] group-hover:scale-110" v-if="!this.editingField" />
                                    </button>
                                </div>
                            </template>

                            <template #saveChanges>
                                <button class="w-fit p-2 hover:bg-gray-200" @click.prevent="handleSaveChange">
                                    <Save class="h-[24px] w-[24px]"  v-if="this.editingField"/>
                                </button>
                            </template>

                            <template #cancelUpdate>
                                <button class="w-fit p-2 hover:bg-gray-200" @click.prevent="handleCancelEdit">
                                    <Close class="h-[24px] w-[24px]" v-if="this.editingField"/>
                                </button>
                            </template>
                        </UserInfoProfileFields>
                    </div>

                    <div>
                        <UserInfoProfileFields>
                            <template #fieldName>
                                <p ref="beforeRenderInput" class="text-[24px] font-bold" for="name">Email:</p>
                            </template>

                            <template #currentDetails>
                                <p class="text-[24px] font-normal group-hover:underline">{{ user.email }}</p>
                            </template>
                        </UserInfoProfileFields>
                    </div>
                    <!-- {{ user }} -->
                    <div>
                        <UserInfoProfileFields>
                            <template #fieldName>
                                <p ref="beforeRenderInput" class="text-[24px] font-bold" for="name">Role:</p>
                            </template>

                            <template #currentDetails>
                                <p class="text-[24px] font-normal group-hover:underline">{{ user.role }}</p>
                            </template>
                        </UserInfoProfileFields>
                    </div>

                    <div>
                        <UserInfoProfileFields>
                            <template #fieldName>
                                <p ref="beforeRenderInput" class="text-[24px] font-bold" for="name">Site:</p>
                            </template>

                            <template #currentDetails>
                                <p class="text-[24px] font-normal group-hover:underline">Edwardstown Primary School
</p>
                            </template>
                        </UserInfoProfileFields>
                    </div>
                    <!-- <div class="col-span-1 row-span-1 flex flex-row gap-4 relative place-items-center">


                        <div class="group">
                            <button class="flex flex-row gap-4 place-items-center">
                                <p class="text-[24px] font-normal group-hover:underline">{{ user.email }}</p>

                            </button>
                        </div>
                    </div> -->
                </div>

            </div>

            <div class="col-span-12 row-span-4 ">
                <nav class="bg-[#1C5CA9]">
                    <ul class="flex flex-row gap-4 mt-[100px] px-[81px] text-white text-[18px] font-bold">
                        <li class="my-2" v-for="subMenuItem in this.subMenuItems">
                            <button @click.prevent="handleProfileSubMenuClick(subMenuItem)" :class="subMenuItem.isActive ? 'underline' : ''" class="py-2 px-4 hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all">{{ subMenuItem.name }}</button>
                        </li>
                    </ul>
                </nav>

                <div class="mt-[100px] px-[81px]" v-if="this.activeProfileField === 'Info'">
                    <div class="grid grid-cols-6 gap-12">
                        <div class="col-span-1 border-r border-slate-300 pr-12">
                            <ul>
                                <li v-for="item in this.userInfoMenu">
                                    <button @click.prevent="handleProfileInfoSelection(item)" :class="item.isActive ? 'underline' : ''" class="w-full text-left px-4 py-2 hover:underline decoration-[#1C5CA9] decoration-4 underline-offset-8 transition-all">
                                        {{ item.name }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="col-span-5">
                            <div v-for="item in this.metadata">
                                <!-- <div v-for="menuItem in this.userInfoMenu"> -->
                                    <!-- {{ item }} -->

                                    <div v-if="item.user_meta_key === 'biography' && this.activeInfoItem === 'Biography'">
                                        <p class="text-[18px] font-medium text-black col-span-3">
                                            {{ item.user_meta_value[0] }}
                                        </p>
                                    </div>

                                    <div v-if="item.user_meta_key === 'yearLevels' && this.activeInfoItem == 'Year levels'" class="flex flex-col gap-4">
                                        <div v-for="(yearLevel, index) in item.user_meta_value" class="yearLevelParent flex bg-gray-100 p-4 rounded-full w-[24px] h-[24px] justify-center place-items-center cursor-pointer hover:bg-gray-300" @click.prevent="this.editIndex = index;">
                                            <p :ref="`yearLevel-${yearLevel}`" class="font-bold" v-if="this.editIndex !== index">
                                                {{ yearLevel }}
                                            </p>

                                            <div v-else class="flex flex-row">
                                                <input type="number" v-model="this.updatedYearLevel" class="!w-[72px] !px-[0.5rem] !py-[0.25rem]">
                                                <button class="w-fit p-2 hover:bg-gray-200" @click.prevent="handleSaveChange">
                                                    <Save class="h-[24px] w-[24px]"  v-if="this.editingField"/>
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                    <div v-if="item.user_meta_key === 'subjects' && this.activeInfoItem === 'Subjects'">
                                        <p v-for="subject in item.user_meta_value">
                                            {{ subject }}
                                        </p>
                                    </div>

                                    <div v-if="item.user_meta_key === 'interests' && this.activeInfoItem === 'Interests'">
                                        <p v-for="interest in item.user_meta_value">
                                            {{ interest }}
                                        </p>
                                    </div>
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-[100px] px-[81px]" v-if="this.activeProfileField === 'Work'">
                    <h3 class="text-[24px] font-normal">Work temporarily disabled</h3>
                </div>

                <div class="mt-[100px] px-[81px]" v-if="this.activeProfileField === 'Messages'">
                    <h3 class="text-[24px] font-normal">Messages temporarily disabled</h3>
                </div>
            </div>

        </div>
    </div>
</template>
