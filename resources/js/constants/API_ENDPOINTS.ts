import {appURL, imageUploadURL, serverURL} from "@/js/constants/serverUrl";

type EndpointGroup = {
    [key: string]: string;
};

type Endpoints = {
    [key: string]: EndpointGroup;
};

const appendServerURL = (endpoints: Endpoints): Endpoints => {
    const result: Endpoints = {};

    for (const [group, paths] of Object.entries(endpoints)) {
        result[group] = {};

        for (const [key, path] of Object.entries(paths)) {
            result[group][key] = `${serverURL}${path}`;
        }
    }

    return result;
};
export const API_ENDPOINTS: Endpoints = appendServerURL({
    SCHOOL: {
        FETCH_ALL_SITES: `/fetchAllSites`,
        FETCH_STAFF_FROM_SITE: `/fetchStaffFromSite/`,
        GET_NOMINATED_USER_FROM_SCHOOL: `/getNominatedUsersFromSchool`,
        NOMINATE_USER_FOR_SCHOOL: `/nominateUserForSchool`,
        DELETE_NOMINATED_USER: `/deleteNominatedUser`,
        CREATE_SCHOOL: `/createSchool`,
        UPDATE_SCHOOL: `/updateSchool`,
        CREATE_OR_UPDATE_SCHOOL_CONTACT: `/createOrUpdateSchoolContact`,
        FETCH_SCHOOL_CONTACT: `/fetchSchoolContact`,
        CHECK_IF_USER_CAN_EDIT_SCHOOL: `/checkUserCanEdit`,
        FETCH_ALL_SCHOOLS: `/fetchAllSchools`,
        FETCH_SCHOOL_BY_NAME: `/fetchSchoolByName/`,
        FETCH_FEATURED_SCHOOL: `/fetchFeaturedSchools`,
        FETCH_PENDING_SCHOOL_BY_NAME: '/fetchPendingSchoolByName/',
        FETCH_USER_SCHOOL: '/fetchUserSchool'
    },
    ADVICE: {
        CREATE_ADVICE_POST: `/createAdvicePost`,
        FETCH_ADVICE_POSTS: `/fetchAdvicePosts`,
        FETCH_ADVICE_POSTS_BY_TYPE_DAG: `/fetchAdvicePostByType/DAG advice`,
        FETCH_ADVICE_POSTS_BY_TYPE_PARTNER: `/fetchAdvicePostByType/Partner`,
        FETCH_ADVICE_POSTS_BY_TYPE_YOUR: `/fetchAdvicePostByType/${['Your Classroom', 'Your Work', 'Your Learning']}`,
        FETCH_ADVICE_POST_BY_ID: `/fetchAdvicePostById`,
        FETCH_RELATED_ADVICE: `/fetchRelatedAdvice`,
        FETCH_ADVICE_TYPES: `/fetchAdviceTypes`,
        FETCH_USER_ADVICE: `/fetchUserAdvice`

    },
    SOFTWARE: {
        CREATE_SOFTWARE_POST: `/createSoftwarePost`,
        FETCH_SOFTWARE_POSTS: `/fetchSoftwarePosts`,
        FETCH_SOFTWARE_POST_BY_ID: `/fetchSoftwarePostById`,
        FETCH_RELATED_SOFTWARE: `/fetchRelatedSoftware`,
        FETCH_SOFTWARE_TYPES: `/fetchSoftwareTypes`,
        FETCH_USER_SOFTWARE: '/fetchUserSoftware'

    },
    HARDWARE: {
        FETCH_HARDWARE_POSTS: `/fetchAllProducts`,
        FETCH_ALL_BRANDS: `/fetchAllBrands`,
        FETCH_ALL_CATEGORIES: `/fetchAllCategories`,
        FETCH_HARDWARE_BY_ID: `/fetchProductById`,
        FETCH_HARDWARE_BY_BRAND: `/fetchProductByBrand/`,
        FETCH_RELATED_HARDWARE: `/fetchRelatedProduct`,
        FETCH_USER_HARDWARE: '/fetchUserProduct'
    },
    PARTNER: {
        FETCH_ALL_PARTNERS: `/fetchAllPartners`,
        FETCH_PARTNER_BY_ID: `/fetchPartnerById`,
        UPDATE_PARTNER_CONTENT: `/updatePartnerContent`,
        FETCH_PARTNER_RESOURCE: '/fetchPartnerResource',
        CHECK_IF_USER_CAN_EDIT_PARTNER: '/checkIfUserCanEditPartner',
        FETCH_PARTNER_PENDING_PROFILE: '/fetchPartnerPendingProfile'
    },
    EVENT: {
        ADD_EVENT_RECORDING: `/addEventRecording`,
        CHECK_EVENT_RECORDING: `/checkEventRecording/`,
        CHECK_IF_USER_RSVPED: `/checkIfUserRsvped`,
        ADD_RSVP_TO_EVENT: `/addRsvpToEvent`,
        FETCH_EVENT_POSTS: `/fetchEventPosts`,
        FETCH_EVENT_POST_BY_ID: `/fetchEventPostById`,
        FETCH_EVENT_TYPES: `/fetchEventTypes`,
        CREATE_EVENT_POST: `/createEventPost`,
        ADD_OR_EDIT_EMS_LINK: `/addOrEditEMSLink`,
        FETCH_EMS_LINK: `/fetchEMSLink/`
    },
    USER: {
        FETCH_CURRENT_USER: `/fetchCurrentUser`,
        FETCH_USER_BY_EMAIL: `/fetchUserByEmail/`,
        CREATE_USER: `/createUser`,
        UPDATE_FIRST_TIME_VISIT_USER: '/updateFirstTimeVisitUser',
        UPDATE_USER: '/updateUser',
        CHECK_EMAIL: `/checkEmail`,
        GET_USER_METADATA: `/getUserMetadata`,
        GET_USER_NOTIFICATION: `/fetchUserNotification`,
        GET_USER_DRAFT_POSTS: `/getUserDraftPosts`,
        UPDATE_OR_CREATE_METADATA:  '/updateOrCreateMetadata/',
        GET_USER_PROFILE_METADATA:  '/getUserProfileMetadata/'
    },
    CATALOGUE: {
        FETCH_CATALOGUE_BY_FIELD: '/fetchCatalogueByField',
        FETCH_SINGLE_PRODUCT_BY_NAME: '/fetchSingleProductByName',
        FETCH_SINGLE_PRODUCT_BY_REFERENCE: '/fetchSingleProductByReference',
        FETCH_UPGRADES_SINGLE_PRODUCT: '/fetchUpgradesSingleProduct',
        FETCH_BUNDLES_SINGLE_PRODUCT: '/fetchBundlesSingleProduct',
        FETCH_ALL_CATALOGUE_CATEGORIES : '/fetchAllCatalogueCategories',
        FETCH_ALL_CATALOGUE_TYPES : '/fetchAllCatalogueTypes',
        FETCH_ALL_CATALOGUE_BRANDS : '/fetchAllCatalogueBrands',
        FETCH_ALL_CATALOGUE_VENDORS : '/fetchAllCatalogueVendors',
        FETCH_ALL_CATALOGUE: '/fetchAllCatalogue'
    },
    LIKE: {
        LIKE: `/like`,
        FETCH_ALL_LIKES: `/fetchAllLikes`,
        FETCH_ALL_LIKES_BY_TYPE: `/fetchAllLikesByType`,
    },
    BOOKMARK: {
        BOOKMARK: `/bookmark`,
        FETCH_ALL_BOOKMARKS: `/fetchAllBookmarks`,
        FETCH_ALL_BOOKMARKS_WITH_TITLE: `/fetchAllBookmarksWithTitle`,
        FETCH_ALL_BOOKMARKS_BY_TYPE: `/fetchAllBookmarksByType`,
    },
    LABEL: {
        FETCH_ALL_LABELS: '/fetchAllLabels'
    },
    SEARCH: {
        SEARCH_ALL: `/search/`
    },
    AUTOSAVE: {
        AUTOSAVE: `/auto-save`
    },
    FEEDBACK: {
        CREATE_FEEDBACK: '/createFeedback'
    }
})

export const APP_ENDPOINTS = {
    LOGOUT: `${appURL}/logout`
}

export const IMAGE_ENDPOINTS = {
    IMAGE: {
        UPLOAD_IMAGE: `${imageUploadURL}/imageUpload`,
        IMAGE_UPLOAD_EDITOR_JS: `${imageUploadURL}/imageUploadEditorjs`,
        IMAGE_UPLOAD_TINYMCE: `${imageUploadURL}/imageUploadTinyMCEjs`

    }
}
