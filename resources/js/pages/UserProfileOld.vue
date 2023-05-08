<script>
    /**
     * Import Dependencies
     */
import { ref, computed } from 'vue';

/**
 * Import Stores
 */
import { useUserStore } from '../stores/useUserStore';
import { useSiteStore } from '../stores/useSiteStore';

/**
 * Import Components
 */
import UserInfoProfileFields from '../components/global/UserInfoProfileFields.vue';

/**
 * SVG's
 */
// import Save from '../components/svg/Save.vue';
// import Close from '../components/svg/Close.vue';
// import Edit from '../components/svg/Edit.vue';

export default {

    components: {
        Edit,
        Save,
        Close,
        UserInfoProfileFields
    },
    setup(refs) {
        const userStore = useUserStore();
        const siteStore = useSiteStore();

        const isEditAvatar = ref(false);

        const updatedName = ref('');

        const handleSaveChange = () => {
            console.log('Handle save values here');
            // userStore.updateUser(updatedName.value);
        };

        const metadata = computed(() => {
            return userStore.getUser.metadata
        });

        const notifications = computed(() => {
            return userStore.getNotifications;
        });

        return {
            userStore,
            siteStore,
            isEditAvatar,
            handleSaveChange,
            updatedName,
            metadata,
            notifications
        };
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
            userAvatar: null,
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
            ],
        }
    },

    mounted() {
        this.handleMetaData();
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
                    case 'userAvatar':
                        this.userAvatar = meta.user_meta_value
                        break;

                    default:
                        this.biography = null;
                        this.yearLevels = null;
                        this.interests = null;
                        this.subjects = null;
                        this.userAvatar = null;
                        break;
                    }
                })
            }
        },

        handleEditBio() {

        },

        updateYearLevels(year) {
            this.yearLevels.push(year);
        },

        fetchUserSite(siteId) {
            // Get the site by id
            return this.siteStore.getSiteById(siteId);
        },
    }

}
</script>

<template>
    <div class="h-full w-full bg-white">
        <div class="w-full flex flex-col">
            <div class="col-span-12 row-span-2 grid grid-cols-6 justify-center mt-[100px] px-[81px]">
                <div
                    class="col-span-1 cursor-pointer h-[200px] w-[200px] bg-orange-500 rounded-full flex justify-center place-items-center relative"
                    @mouseenter="isEditAvatar = !isEditAvatar"
                    @mouseleave="isEditAvatar = !isEditAvatar"
                >
                    <h1 class="text-[72px] text-white font-bold">
                        {{ user.display_name }}
                    </h1>
                    <div
                        v-if="isEditAvatar"
                        class="absolute w-[48px] h-[48px] rounded-full bg-white flex justify-center place-items-center top-0 right-[24px] border border-black border-2"
                    >
                        <Edit class="w-[24px] h-24px" />
                    </div>
                </div>

                <!-- User information component -->
                <!-- Use slots -->

                <div class="col-span-5 grid grid-rows-4 grid-cols-1">
                    <div>
                        <UserInfoProfileFields>
                            <template #updateField>
                                <input
                                    v-if="editingField"
                                    ref="editName"
                                    v-model="updatedName"
                                    class="!w-[320px]"
                                    type="text"
                                    :placeholder="user.full_name"
                                >
                            </template>

                            <template #fieldName>
                                <p
                                    ref="beforeRenderInput"
                                    class="text-[24px] font-bold"
                                    for="name"
                                >
                                    Name:
                                </p>
                            </template>

                            <template #currentDetails>
                                <div
                                    v-if="!editingField"
                                    class="group"
                                >
                                    <button
                                        class="flex flex-row gap-4 place-items-center"
                                        @click.prevent="editField"
                                    >
                                        <p
                                            v-if="!editingField"
                                            class="text-[24px] font-normal group-hover:underline"
                                        >
                                            {{ user.full_name }}
                                        </p>
                                        <Edit
                                            v-if="!editingField"
                                            class="h-[18px] group-hover:scale-110"
                                        />
                                    </button>
                                </div>
                            </template>

                            <template #saveChanges>
                                <button
                                    class="w-fit p-2 hover:bg-gray-200"
                                    @click.prevent="handleSaveChange"
                                >
                                    <Save
                                        v-if="editingField"
                                        class="h-[24px] w-[24px]"
                                    />
                                </button>
                            </template>

                            <template #cancelUpdate>
                                <button
                                    class="w-fit p-2 hover:bg-gray-200"
                                    @click.prevent="handleCancelEdit"
                                >
                                    <Close
                                        v-if="editingField"
                                        class="h-[24px] w-[24px]"
                                    />
                                </button>
                            </template>
                        </UserInfoProfileFields>
                    </div>

                    <div>
                        <UserInfoProfileFields>
                            <template #fieldName>
                                <p
                                    ref="beforeRenderInput"
                                    class="text-[24px] font-bold"
                                    for="name"
                                >
                                    Email:
                                </p>
                            </template>

                            <template #currentDetails>
                                <p class="text-[24px] font-normal group-hover:underline">
                                    {{ user.email }}
                                </p>
                            </template>
                        </UserInfoProfileFields>
                    </div>
                    <!-- {{ user }} -->
                    <div>
                        <UserInfoProfileFields>
                            <template #fieldName>
                                <p
                                    ref="beforeRenderInput"
                                    class="text-[24px] font-bold"
                                    for="name"
                                >
                                    Role:
                                </p>
                            </template>

                            <template #currentDetails>
                                <p class="text-[24px] font-normal group-hover:underline">
                                    {{ user.role }}
                                </p>
                            </template>
                        </UserInfoProfileFields>
                    </div>

                    <div>
                        <UserInfoProfileFields>
                            <template #fieldName>
                                <p
                                    ref="beforeRenderInput"
                                    class="text-[24px] font-bold"
                                    for="name"
                                >
                                    Site:
                                </p>
                            </template>

                            <template #currentDetails>
                                <p class="text-[24px] font-normal group-hover:underline">
                                    {{ fetchUserSite(user.site_id) }}
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

            <div class="col-span-12 row-span-4 mx-20">
                <nav class="bg-[#1C5CA9]">
                    <ul class="flex flex-row gap-4 mt-[100px] px-[81px] text-white text-[18px] font-bold">
                        <li
                            v-for="subMenuItem in subMenuItems"
                            class="my-2"
                        >
                            <button
                                :class="subMenuItem.isActive ? 'underline' : ''"
                                class="py-2 px-4 hover:underline decoration-[#B8E2DC] decoration-4 underline-offset-8 transition-all"
                                @click.prevent="handleProfileSubMenuClick(subMenuItem)"
                            >
                                {{ subMenuItem.name }}
                            </button>
                        </li>
                    </ul>
                </nav>

                <div
                    v-if="activeProfileField === 'Info'"
                    class="mt-[100px] "
                >
                    <div class="grid grid-cols-6 gap-12">
                        <div class="col-span-1 border-r border-slate-300 pr-12">
                            <ul>
                                <li v-for="item in userInfoMenu">
                                    <button
                                        :class="item.isActive ? 'underline' : ''"
                                        class="w-full text-left px-4 py-2 hover:underline decoration-[#1C5CA9] decoration-4 underline-offset-8 transition-all"
                                        @click.prevent="handleProfileInfoSelection(item)"
                                    >
                                        {{ item.name }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="col-span-5">
                            <div v-for="item in metadata">
                                <!-- <div v-for="menuItem in this.userInfoMenu"> -->
                                <!-- {{ item }} -->

                                <div v-if="activeInfoItem === 'Biography'">
                                    <div v-if="biography !== null">
                                        <div
                                            v-if="!editBio"
                                            @click.prevent="editBio = !editBio"
                                        >
                                            <p class="text-[18px] font-medium text-black col-span-3">
                                                {{ biography }}
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                        v-if="editBio"
                                        class="flex flex-col gap-6"
                                    >
                                        <label for="bio">Edit your bio</label>
                                        <textarea
                                            id="bio"
                                            ref="bioTextarea"
                                            v-model="biography"
                                            name="bio"
                                            class="min-h-[190px]"
                                        />

                                        <div class="flex justify-self-end flex-row gap-4 ml-auto">
                                            <button
                                                class="px-4 py-2 bg-transparent border-2 border-[#1C5CA9] text-[#1C5CA9] rounded-lg hover:bg-[#1C5CA9] hover:text-white"
                                                @click.prevent="editBio = !editBio"
                                            >
                                                Cancel
                                            </button>

                                            <button class="px-4 py-2 bg-[#1C5CA9] border-2 border-[#1C5CA9] text-white rounded-lg hover:bg-[#143965] hover:border-[#143965]">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="activeInfoItem === 'Year levels'">
                                    <div v-if="editYearLevels">
                                        <p
                                            v-for="year in yearLevels"
                                            class="h-[24px] w-[24px] bg-gray-100 rounded-full flex place-items-center justify-center"
                                        >
                                            {{ year }}
                                        </p>
                                    </div>
                                    <div v-if="!editYearLevels">
                                        <label for="yearLevels">What year levels do you teach?</label>

                                        <div class="flex flex-col gap-6">
                                            <div class="flex flex-row gap-3 w-full">
                                                <div v-for="(year, index) in theYearLevels">
                                                    <label :for="year">{{ year }}</label>
                                                    <input
                                                        :id="year"
                                                        type="checkbox"
                                                        :value="year"
                                                    >
                                                </div>
                                            </div>

                                            <div class="flex justify-self-end flex-row gap-4 ml-auto">
                                                <button
                                                    class="px-4 py-2 bg-transparent border-2 border-[#1C5CA9] text-[#1C5CA9] rounded-lg hover:bg-[#1C5CA9] hover:text-white"
                                                    @click.prevent="editBio = !editBio"
                                                >
                                                    Cancel
                                                </button>

                                                <button
                                                    class="px-4 py-2 bg-[#1C5CA9] border-2 border-[#1C5CA9] text-white rounded-lg hover:bg-[#143965] hover:border-[#143965]"
                                                    @click="updateYearLevels(year)"
                                                >
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="activeInfoItem === 'Subjects'">
                                    <div v-if="subjects !== null">
                                        YA YA YA, I AM SUBJECTS
                                    </div>
                                    <div v-else>
                                        NO SUBJECT
                                    </div>
                                </div>

                                <div v-if="activeInfoItem === 'Interests'">
                                    <div v-if="interests !== null">
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

                <div
                    v-if="activeProfileField === 'Work'"
                    class="mt-[100px] px-[81px]"
                >
                    <h3 class="text-[24px] font-normal">
                        Work temporarily disabled
                    </h3>
                </div>

                <div
                    v-if="activeProfileField === 'Messages'"
                    class="mt-[100px] px-[81px]"
                >
                    <h3 class="text-[24px] font-normal">
                        Messages temporarily disabled
                    </h3>
                    {{ notifications }}
                </div>
            </div>
        </div>
    </div>
</template>


<div class="UserProfileContainer flex flex-col ">
<div class="w-full flex flex-col bg-slate-100">
    <div class="col-span-12 row-span-2 grid grid-cols-6 justify-center mt-[100px] px-[81px]">
        <div
            class="col-span-1 cursor-pointer h-[200px] w-[200px] bg-orange-500 rounded-full flex justify-center place-items-center relative"
            @mouseenter="isEditAvatar = !isEditAvatar"
            @mouseleave="isEditAvatar = !isEditAvatar"
        >
            <h1 class="text-[72px] text-white font-bold">
                {{ currentUser.display_name }}
            </h1>
            <div
                v-if="isEditAvatar"
                class="absolute w-[48px] h-[48px] rounded-full bg-white flex justify-center place-items-center top-0 right-[24px] border border-black border-2"
            />
        </div>
    </div>


    <p class="text-[24px] font-bold">
        Name:
    </p>
    <input
        v-if="editingInfoField"
        ref="editName"
        v-model="updatedName"
        class="!w-[320px]"
        type="text"
        :placeholder="currentUser['full_name']"
    >
    <div
        v-if="!editingInfoField"
        class="group"
    >
        <button
            class="flex flex-row gap-4 place-items-center"
            @click.prevent="editField"
        >
            <p
                v-if="!editingInfoField"
                class="text-[24px] font-normal group-hover:underline"
            >
                {{ currentUser['full_name'] }}
            </p>
            <Edit
                v-if="!editingInfoField"
                class="h-[18px] group-hover:scale-110"
            />
        </button>
        <button
            class="w-fit p-2 hover:bg-gray-200"
            @click.prevent="handleSaveChange"
        >
            <Save
                v-if="editingInfoField"
                class="h-[24px] w-[24px]"
            />
        </button>
    </div>
    <button
        class="w-fit p-2 hover:bg-gray-200"
        @click.prevent="handleSaveChange"
    >
        <Save
            v-if="editingInfoField"
            class="h-[24px] w-[24px]"
        />
    </button>
    <UserProfileSubmenu :submenu-items="subMenuItems" />
</div>
<router-view />
</div>
