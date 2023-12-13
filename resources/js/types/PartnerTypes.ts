import {EditorJSDataType} from "@/js/types/EditorJsTypes";


type Metadata = {
    partner_meta_key: string;
    partner_meta_value: string;
};


export type PartnerDataType = {
    id: number;
    user_id: number;
    name: string;
    email: string;
    logo: string;
    cover_image: string;
    motto: string;
    introduction: string;
    content: EditorJSDataType;
    metadata: Metadata[];
    isLikedByUser: boolean;
    isBookmarkedByUser: boolean;
    profile: string;
};
