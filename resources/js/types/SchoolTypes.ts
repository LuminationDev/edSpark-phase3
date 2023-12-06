import {EditorJSDataType} from "@/js/types/EditorJsTypes";

export type SchoolDataType = {
    school_id: number;
    site: Site;
    owner: Owner;
    name: string;
    content_blocks: string;
    logo: string;
    cover_image: string;
    tech_used: TechUsed[];
    status: string;
    pedagogical_approaches: any;  // Given as null in the provided data. Adjust type if it can have other data structures.
    tech_landscape: any;  // Same as above.
    metadata: Metadata[];
    location: Location;
    isLikedByUser: boolean;
    isBookmarkedByUser: boolean;
};

type Site = {
    site_id: number;
    site_name: string;
};

type Owner = {
    owner_id: number;
    owner_name: string;
};

export type TechUsed = {
    name: string;
    description: string;
    category: string;
    provider: string;
};

type Metadata = {
    schoolmeta_key: string;
    schoolmeta_value: string;
};

type Location = {
    lat: number;
    lng: number;
};

