<script>
    /**
     * Import Dependencies
     */
    import { ref, computed } from 'vue';

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
        setup(refs) {
            const userStore = useUserStore();

            const isEditAvatar = ref(false);

            const updatedName = ref('');

            const handleSaveChange = () => {
                console.log('Handle save values here');
                // userStore.updateUser(updatedName.value);
            };

            const metadata = computed(() => {
                return userStore.getUser.metadata
            });


            return {
                userStore,
                isEditAvatar,
                handleSaveChange,
                updatedName,
                metadata,
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
                // metadata: this.userStore.getUser.metadata,
                biography: null,
                interests: null,
                subjects: null,
                yearLevels: [],
                editingField: false,
                editBio: false,
                editYearLevels: false,
                theYearLevels: [
                    1,2,3,4,5,6,7,8,9,10,11,12,13
                ],
                userInfoMenu: [
                    {
                        name: 'Biography',
                        isActive: true
                    },
                    {
                        name: 'Year levels',
                        isActive: false
                    },
                    {
                        name: 'Subjects',
                        isActive: false
                    },
                    {
                        name: 'Interests',
                        isActive: false
                    }
                ],
                activeProfileField: 'Info',
                activeInfoItem: 'Biography',
                updatedYearLevel: 0,
                editIndex: null,
                replacementMeta: [
                    'biography',
                    'yearLevels',
                    'subjects',
                    'interests'
                ],
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
            handleMetaData() {
                console.log(this.metadata);
                if (this.metadata) {
                    this.metadata.forEach(meta => {
                        switch (meta.user_meta_key) {
                            case 'biography':
                                if ( typeof meta.user_meta_value === 'string') {
                                    this.biography = meta.user_meta_value
                                } else {
                                    this.biography = meta.user_meta_value.join(', ');
                                }
                                break;
                            case 'yearLevels':
                                    this.yearLevels = meta.user_meta_value
                                break;
                            case 'interests':
                                    this.interests = meta.user_meta_value
                                break;
                            case 'subjects':
                                    this.subjects = meta.user_meta_value
                                break;

                            default:
                                this.biography = null;
                                this.yearLevels = null;
                                this.interests = null;
                                this.subjects = null
                                break;
                        }
                    })
                }
            },

            handleEditBio() {

            },

            updateYearLevels(year) {
                this.yearLevels.push(year);
            }
        },

        mounted() {
            this.handleMetaData();
            console.log(this.biography);
            console.log(this.yearLevels);
            console.log(this.subjects);
            console.log(this.interests);

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

                                    <div v-if="this.activeInfoItem === 'Biography'">
                                        <div  v-if="this.biography !== null">
                                            <div v-if="!this.editBio" @click.prevent="this.editBio = !this.editBio">
                                                <p class="text-[18px] font-medium text-black col-span-3">
                                                    {{ this.biography }}
                                                </p>
                                            </div>

                                        </div>
                                        <div v-if="this.editBio" class="flex flex-col gap-6">
                                            <label for="bio">Edit your bio</label>
                                            <textarea ref="bioTextarea" name="bio" id="bio" v-model="this.biography" class="min-h-[190px]"></textarea>

                                            <div class="flex justify-self-end flex-row gap-4 ml-auto">
                                                <button @click.prevent="this.editBio = !this.editBio" class="px-4 py-2 bg-transparent border-2 border-[#1C5CA9] text-[#1C5CA9] rounded-lg hover:bg-[#1C5CA9] hover:text-white">
                                                    Cancel
                                                </button>

                                                <button class="px-4 py-2 bg-[#1C5CA9] border-2 border-[#1C5CA9] text-white rounded-lg hover:bg-[#143965] hover:border-[#143965]">
                                                    Save
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                    <div v-if="this.activeInfoItem === 'Year levels'">
                                        <div v-if="this.editYearLevels">
                                            <p v-for="year in this.yearLevels" class="h-[24px] w-[24px] bg-gray-100 rounded-full flex place-items-center justify-center">
                                                {{ year }}
                                            </p>
                                        </div>
                                        <div v-if="!this.editYearLevels">
                                            <label for="yearLevels">What year levels do you teach?</label>

                                            <div class="flex flex-col gap-6">
                                                <div class="flex flex-row gap-3 w-full">
                                                    <div v-for="(year, index) in this.theYearLevels">
                                                        <label :for="year">{{ year }}</label>
                                                        <input type="checkbox" :id="year" :value="year">
                                                    </div>
                                                </div>

                                                <div class="flex justify-self-end flex-row gap-4 ml-auto">
                                                    <button @click.prevent="this.editBio = !this.editBio" class="px-4 py-2 bg-transparent border-2 border-[#1C5CA9] text-[#1C5CA9] rounded-lg hover:bg-[#1C5CA9] hover:text-white">
                                                        Cancel
                                                    </button>

                                                    <button @click="updateYearLevels(year)" class="px-4 py-2 bg-[#1C5CA9] border-2 border-[#1C5CA9] text-white rounded-lg hover:bg-[#143965] hover:border-[#143965]">
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div v-if="this.activeInfoItem === 'Subjects'">
                                        <div v-if="this.subjects !== null">
                                            YA YA YA, I AM SUBJECTS
                                        </div>
                                        <div v-else>
                                            NO SUBJECT
                                        </div>
                                    </div>

                                    <div v-if="this.activeInfoItem === 'Interests'">
                                        <div v-if="this.interests !== null">
                                            YA YA YA, I AM INTERSEST
                                        </div>
                                        <div v-else>
                                            NO INTERSEST
                                        </div>
                                    </div>
                                    <!-- <div v-if="item.user_meta_key === 'biography' && this.activeInfoItem === 'Biography'">
                                        <div v-for="(biography, index) in item.user_meta_value" @click.prevent="this.editIndex = index;">
                                            <p v-if="this.editIndex !== index" @click.prevent="this.editIndex = index;" class="text-[18px] font-medium text-black col-span-3">
                                                {{ biography }}
                                            </p>

                                            <div v-else ref="bioInput">
                                                <textarea name="" id="" cols="30" rows="10"></textarea>
                                            </div>
                                        </div>

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
                                    </div> -->
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
