import {ExtraContentFilamentType} from "@/js/types/PostTypes";

export type EventType = {
    id: number;
    title: string;
    content: string;
    excerpt: string;
    location: {
        url: string;
        address?: string;
    };
    author: {
        author_id: number;
        author_name: string;
        author_email: string;
        author_type: string;
        author_logo: string;
    };
    cover_image: string;
    start_date: string;
    end_date: string;
    status: string;
    type: string;
    created_at: string;
    updated_at: string;
    extra_content: ExtraContentFilamentType[];
    isLikedByUser: boolean;
    isBookmarkedByUser: boolean;
    tags: string[];
};

interface EventLocationType {
    url?: string,
    address?: string
}

export type EventAdditionalData = {
    extra_content: Array<any>,
    type: number,
    location: EventLocationType
    start_date: Date,
    end_date: Date
}